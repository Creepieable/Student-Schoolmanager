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
//note handling \/
//--------------------

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    getNotes();
}
else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    addNodes();
}
else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    dropNodes();
}
else if ($_SERVER['REQUEST_METHOD'] === 'PATCH') {
    if(isset($_SERVER['HTTP_NOTE_IDS'])){
        updateNodesTaskID();
    }
    else {
        editNote();
    }
}
else{
    $regObj = new \stdClass();
    $regObj->type = 'notes';
    $regObj->status = 'error';
    $regObj->error = 'wrong request method';

    $respJSON = json_encode($regObj);
    echo $respJSON;
    http_response_code(405); exit();
}

function getNotes(){
    global $db, $token;
    $noteIDs = [];
    $stmt = NULL;

    if(isset($_SERVER['HTTP_NOTE_IDS'])){
        //split CSV IDs into array
        $noteIDs = explode(',', $_SERVER['HTTP_NOTE_IDS']);

        //prepare array stmt
        $count = count($noteIDs);
        $placeholders = implode(',', array_fill(0, $count, '?'));
        $bindStr = str_repeat('i', $count);

        $stmt = $db->prepare("SELECT notes.noteID, notes.title, notes.text, notes.colour FROM notes 
        INNER JOIN logintokens ON notes.userID = logintokens.userID 
            WHERE logintokens.token = ?
            AND noteID IN ($placeholders)
            ORDER BY notes.noteID;");
        $stmt->bind_param("s".$bindStr, $token,...$noteIDs);
    }
    else{
        //prepare all stmt
        $stmt = $db->prepare("SELECT notes.noteID, notes.title, notes.text, notes.colour FROM notes 
                                INNER JOIN logintokens ON notes.userID = logintokens.userID 
                                WHERE logintokens.token = ?
                                ORDER BY notes.noteID;");
        $stmt->bind_param("s", $token);   
    }
    try{
        $stmt->execute();
    } catch (Exception $e) {
        $regObj = new \stdClass();
        $regObj->type = 'notes';
        $regObj->status = 'error';
        $regObj->error = 'unknown';

        $respJSON = json_encode($regObj);
        echo $respJSON;
        http_response_code(500); exit();   
    }

    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $respObj = new \stdClass();
    $respObj->type = 'notes';

    $notes = [];
    foreach ($result as &$note){
        $taskObj = new \stdClass();
        $taskObj->noteID = $note["noteID"];
        $taskObj->title = $note["title"];
        $taskObj->text = $note["text"];
        $taskObj->colour = $note["colour"];

        array_push($notes, $taskObj);
    }

    $respObj->notes = $notes;

    $respJSON = json_encode($respObj);
    echo $respJSON; exit();
}

function addNodes(){
    global $db, $token;  
    //get request json
    $requestBody = trim(file_get_contents("php://input"));
    $requestObj = json_decode($requestBody);

    $title = $requestObj->data->title;
    $text = $requestObj->data->text;
    $taskID = $requestObj->data->taskID;
    $colour = $requestObj->data->colour;

    try {
        $stmt = $db->prepare('INSERT INTO notes (userID, title, text, taskID, colour)
                                VALUES ((SELECT userID FROM logintokens 
                                    WHERE logintokens.token = ?),
                                ?, ?, ?, ?);');
        $stmt->bind_param('sssis', $token, $title, $text, $taskID, $colour);
        $stmt->execute();
    } catch (Exception $e) {
        $regObj = new \stdClass();
        $regObj->type = 'notes';
        $regObj->status = 'error';
        $regObj->error = 'unknown';

        $respJSON = json_encode($regObj);
        echo $respJSON;
        http_response_code(500); exit();   
    }

    if($db -> affected_rows > 0){
        $respObj = new \stdClass();
        $respObj->type = 'notes';
        $respObj->status = 'done';
        $respObj->added = $db->insert_id;

        $respJSON = json_encode($respObj);
        echo $respJSON; exit();
    }
    else{
        $regObj = new \stdClass();
        $regObj->type = 'notes';
        $regObj->status = 'warning';
        $regObj->warning = 'some notes not effected';

        $respJSON = json_encode($regObj);
        echo $respJSON; exit();
    }
}

function dropNodes(){
    global $db, $token;
    if(!isset($_SERVER['HTTP_NOTE_ID'])){
        $respObj = new \stdClass();
        $respObj->type = 'calendar';
        $respObj->status = 'error';
        $respObj->error = 'missing header data';
    
        $respJSON = json_encode($respObj);
        echo $respJSON;
        http_response_code(400); exit();
    }
    $noteID = $_SERVER['HTTP_NOTE_ID'];
    
    try {
    $stmt = $db->prepare('DELETE FROM notes 
                            WHERE userID = ( SELECT userID FROM logintokens 
                                WHERE logintokens.token = ?)
                            AND notes.noteID = ?;');

    $stmt->bind_param('si', $token, $noteID);

    $stmt->execute();

    } catch (Exception $e) {
        $regObj = new \stdClass();
        $regObj->type = 'notes';
        $regObj->status = 'error';
        $regObj->error = 'unknown';

        $respJSON = json_encode($regObj);
        echo $respJSON;
        http_response_code(500); exit();   
    }

    //send response as JSON
    if($db -> affected_rows > 0){
        $regObj = new \stdClass();
        $regObj->type = 'notes';
        $regObj->status = 'done';

        $respJSON = json_encode($regObj);
        echo $respJSON; exit();
    }
    else{
        $regObj = new \stdClass();
        $regObj->type = 'notes';
        $regObj->status = 'warning';
        $regObj->status = 'no effect';

        $respJSON = json_encode($regObj);
        echo $respJSON; exit();
    }
}

function updateNodesTaskID(){
    global $db, $token;
    if(isset($_SERVER['HTTP_TASK_ID'])) $taskID = $_SERVER['HTTP_TASK_ID'];
    else $taskID = NULL;

    //split CSV IDs into array
    $noteIDs = explode(',', $_SERVER['HTTP_NOTE_IDS']);

    //prepare array stmt
    $count = count($noteIDs);
    $placeholders = implode(',', array_fill(0, $count, '?'));
    $bindStr = str_repeat('i', $count);

    try{
        if($taskID != NULL){
            $stmt = $db->prepare("UPDATE notes
                                    SET taskID = ?
                                        WHERE userID = (SELECT userID FROM logintokens WHERE logintokens.token = ?)
                                    AND noteID IN ($placeholders);");
            $stmt->bind_param("is".$bindStr, $taskID, $token, ...$noteIDs);
        }
        else{
            $stmt = $db->prepare("UPDATE notes
                                    SET taskID = null
                                        WHERE userID = (SELECT userID FROM logintokens WHERE logintokens.token = ?)
                                    AND noteID IN ($placeholders);");
            $stmt->bind_param("s".$bindStr, $token, ...$noteIDs);
        } 
        $stmt->execute();
    } catch (Exception $e) {
        $regObj = new \stdClass();
        $regObj->type = 'notes';
        $regObj->status = 'error';
        $regObj->error = 'unknown';

        $respJSON = json_encode($regObj);
        echo $respJSON;
        http_response_code(500); exit();   
    }

    if($db->affected_rows == count($noteIDs)){
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

function editNote(){
    global $db, $token;
    if(!isset($_SERVER['HTTP_NOTE_ID'])){
        $respObj = new \stdClass();
        $respObj->type = 'calendar';
        $respObj->status = 'error';
        $respObj->error = 'missing header data';
    
        $respJSON = json_encode($respObj);
        echo $respJSON;
        http_response_code(400); exit();
    }

    $noteID = $_SERVER['HTTP_NOTE_ID'];

    //get request json
    $requestBody = trim(file_get_contents("php://input")); 
    $requestObj = json_decode($requestBody);

    $title = $requestObj->data->title;
    $text = $requestObj->data->text;
    $colour = $requestObj->data->colour;

    try{
        $stmt = $db->prepare("UPDATE notes
                                SET title = ?, text = ?, colour = ?
                                    WHERE userID = (SELECT userID FROM logintokens WHERE logintokens.token = ?)
                                    AND noteID = ?;");
        $stmt->bind_param("ssssi", $title, $text, $colour, $token, $noteID);
        $stmt->execute();        
    } catch (Exception $e) {
        $regObj = new \stdClass();
        $regObj->type = 'notes';
        $regObj->status = 'error';
        $regObj->error = 'unknown';

        $respJSON = json_encode($regObj);
        echo $respJSON;
        http_response_code(500); exit();   
    }

    if($db->affected_rows > 0){
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
        $regObj->warning = 'no effect';

        $respJSON = json_encode($regObj);
        echo $respJSON; exit();
    }
}
?>