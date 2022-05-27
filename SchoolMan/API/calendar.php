<?php
include 'credentials.php';

//setup DB connection
$db = new mysqli($dbHost, $dbUsr, $dbPw, $dbName);
if ($db -> connect_errno) {
    $regObj = new \stdClass();
    $regObj->type = 'calendar';
    $regObj->status = 'error';
    $regObj->error = 'server database error';

    $respJSON = json_encode($regObj);
    echo $respJSON;
    http_response_code(500); exit(); 
}

//set json header
header('Content-Type: application/json; charset=utf-8');

//check user token header
if(!isset($_SERVER['HTTP_USR_TOKEN'])){
    $respObj = new \stdClass();
    $respObj->type = 'calendar';
    $respObj->status = 'error';
    $respObj->error = 'missing header data';

    $respJSON = json_encode($respObj);
    echo $respJSON;
    http_response_code(400); exit();
}

$token = $_SERVER['HTTP_USR_TOKEN'];

//--------------------
//calendar handling \/
//--------------------

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    getCalendarEntrys();
}
else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    addCalendarEntry();
}
else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    dropCalendarEntry();
}
else{
    $regObj = new \stdClass();
    $regObj->type = 'calender';
    $regObj->status = 'error';
    $regObj->error = 'wrong request method';

    $respJSON = json_encode($regObj);
    echo $respJSON;
    http_response_code(405); exit();
}

function addCalendarEntry(){
    global $db, $token;
    //get request json
    $requestBody = trim(file_get_contents("php://input"));
    $requestObj = json_decode($requestBody);

    $title = $requestObj->data->title;
    $dueStamp = $requestObj->data->dueStamp;
    $isTimed = $requestObj->data->isTimed;
    $colour = $requestObj->data->colour;

    $noteIDs = $requestObj->data->noteIDs;

    try {
        $stmt = $db->prepare('INSERT INTO tasks (userID, title, dueBy, isTimed, colour)
                                VALUES ((SELECT userID FROM logintokens 
                                    WHERE logintokens.token = ?),
                                ?, UNIX_TIMESTAMP(?), ?, ?);');
        $stmt->bind_param('ssiii', $token, $title, $dueStamp, $isTimed, $colour);
        $stmt->execute();
    } catch (Exception $e) {
        $regObj = new \stdClass();
        $regObj->type = 'calendar';
        $regObj->status = 'error';
        $regObj->error = 'unknown';

        $respJSON = json_encode($regObj);
        echo $respJSON;
        http_response_code(500); exit();   
    }

    $last_id = $db->insert_id;
    $effected = 0;
    if(count($noteIDs)>0){
        //prepare array stmt
        $count = count($noteIDs);
        $placeholders = implode(',', array_fill(0, $count, '?'));
        $bindStr = str_repeat('i', $count);

        try{
            $stmt = $db->prepare("UPDATE notes
                                    SET taskID = ?
                                        WHERE userID = (SELECT userID FROM logintokens WHERE logintokens.token = ?)
                                    AND noteID IN ($placeholders);");
            $stmt->bind_param("is".$bindStr, $last_id, $token, ...$noteIDs);
            $stmt->execute();
        } catch (Exception $e) {
            $regObj = new \stdClass();
            $regObj->type = 'calendar';
            $regObj->status = 'error';
            $regObj->error = 'unknown';

            $respJSON = json_encode($regObj);
            echo $respJSON;
            http_response_code(500); exit();   
        }

        $effected = $db->affected_rows;
    }
    
    if($effected == count($noteIDs)){
        $respObj = new \stdClass();
        $respObj->type = 'notes';
        $respObj->status = 'done';

        $respJSON = json_encode($respObj);
        echo $respJSON; exit();
    }
    else{
        $regObj = new \stdClass();
        $regObj->type = 'notes';
        $regObj->status = 'warning';
        $regObj->warning = count($noteIDs)-$db->affected_rows.' notes not effected';

        $respJSON = json_encode($regObj);
        echo $respJSON; exit();
    }
}  

function getCalendarEntrys(){
    global $db, $token;
    $stmt = NULL;
    $fromDate = NULL;
    $toDate = NULL;
    if(isset($_SERVER['HTTP_CALENDAR_FROM_STAMP']) || isset($_SERVER['HTTP_CALENDAR_TO_STAMP'])){
        $fromDate = $_SERVER['HTTP_CALENDAR_FROM_STAMP'];
        $toDate = $_SERVER['HTTP_CALENDAR_TO_STAMP'];
        $stmt = $db->prepare('SELECT taskID, title, UNIX_TIMESTAMP(dueBy) AS dueBy, isTimed, colour FROM tasks 
                                INNER JOIN logintokens ON tasks.userID = logintokens.userID 
                                WHERE logintokens.token = ?
                                        AND UNIX_TIMESTAMP(dueBy) BETWEEN ? AND ?
                                ORDER BY UNIX_TIMESTAMP(dueBy);');
        $stmt->bind_param('sii', $token, $fromDate, $toDate);
    }
    else{
        $stmt = $db->prepare('SELECT taskID, title, UNIX_TIMESTAMP(dueBy) AS dueBy, isTimed, colour FROM tasks 
                                INNER JOIN logintokens ON tasks.userID = logintokens.userID 
                                WHERE logintokens.token = ?
                                ORDER BY UNIX_TIMESTAMP(dueBy);');
        $stmt->bind_param('s', $token);   
    }
    
    try {
        $stmt->execute();
    
    } catch (Exception $e) {
        $regObj = new \stdClass();
        $regObj->type = 'calendar';
        $regObj->status = 'error';
        $regObj->error = 'unknown';
        $regObj->error = 'error getting calendar';

        $respJSON = json_encode($regObj);
        echo $respJSON;
        http_response_code(500); exit();   
    }

    $taskResult = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    //getTaskIDs
    $taskIDs = [];
    foreach ($taskResult as &$entry){
        array_push($taskIDs, $entry["taskID"]);
    }
    
    //prepare array stmt
    $count = count($taskIDs);
    $placeholders = implode(',', array_fill(0, $count, '?'));
    $bindStr = str_repeat('i', $count);

    //get noteIDs for every requested task
    $noteIDsArr =  [];
    if(count($taskResult) > 0){
        try {
            $sql = "SELECT tasks.taskID, notes.noteID FROM notes
                        INNER JOIN tasks ON tasks.taskID = notes.taskID
                        INNER JOIN logintokens ON tasks.userID = logintokens.userID 
                        WHERE logintokens.token = ?
                        AND notes.taskID IN ($placeholders);";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("s".$bindStr, $token, ...$taskIDs);
            $stmt->execute();
        
        } catch (Exception $e) {
            $regObj = new \stdClass();
            $regObj->type = 'calendar';
            $regObj->status = 'error';
            $regObj->error = 'unknown';
            $regObj->error = 'error getting note IDs';

            $respJSON = json_encode($regObj);
            echo $respJSON;
            http_response_code(500); exit();   
        }

        $idsResult = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        //build array with noteIDs
        foreach ($idsResult as &$entry){
            if(!isset($noteIDsArr[$entry["taskID"]])) $noteIDsArr[$entry["taskID"]] = [];
            if(!is_array($noteIDsArr[$entry["taskID"]])) $noteIDsArr[$entry["taskID"]] = [];
            array_push($noteIDsArr[$entry["taskID"]], $entry["noteID"]);
        }
    }

    //build Response
    $respObj = new \stdClass();
    $respObj->type = 'calendarTasks';
    $respObj->from = $fromDate;
    $respObj->to = $toDate;

    $tasks = [];
    foreach ($taskResult as &$entry){
        $taskObj = new \stdClass();
        $taskObj->taskID = $entry["taskID"];
        $taskObj->title = $entry["title"];
        $taskObj->dueBy = $entry["dueBy"];
        $taskObj->isTimed = boolval($entry["isTimed"]);
        $taskObj->color = $entry["colour"];
        if(isset($noteIDsArr[$entry["taskID"]])){
            $taskObj->notes = $noteIDsArr[$entry["taskID"]];
        }
        else{
            $taskObj->notes = NULL;   
        }

        array_push($tasks, $taskObj);
    }

    $respObj->tasks = $tasks;

    $respJSON = json_encode($respObj);
    echo $respJSON; exit();
}

function dropCalendarEntry(){
    global $db, $token;
    if(!isset($_SERVER['HTTP_CALENDAR_ENTRY_ID'])){
        $respObj = new \stdClass();
        $respObj->type = 'calendar';
        $respObj->status = 'error';
        $respObj->error = 'missing header data';
    
        $respJSON = json_encode($respObj);
        echo $respJSON;
        http_response_code(400); exit();
    }
    $entry = $_SERVER['HTTP_CALENDAR_ENTRY_ID'];
    
    try {
    $stmt = $db->prepare('DELETE FROM tasks 
                            WHERE userID = ( SELECT userID FROM logintokens 
                                WHERE logintokens.token = ?)
                            AND tasks.taskID = ?;');

    $stmt->bind_param('ss', $token, $entry);

    $stmt->execute();

    } catch (Exception $e) {
        $regObj = new \stdClass();
        $regObj->type = 'calendar';
        $regObj->status = 'error';
        $regObj->error = 'unknown';

        $respJSON = json_encode($regObj);
        echo $respJSON;
        http_response_code(500); exit();   
    }

    //send response as JSON
    if($db -> affected_rows > 0){
        $regObj = new \stdClass();
        $regObj->type = 'calendar';
        $regObj->status = 'done';

        $respJSON = json_encode($regObj);
        echo $respJSON; exit();
    }
    else{
        $regObj = new \stdClass();
        $regObj->type = 'calendar';
        $regObj->status = 'warning';
        $regObj->status = 'no effect';

        $respJSON = json_encode($regObj);
        echo $respJSON; exit();
    }
}
?>