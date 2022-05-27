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
//schedule handling \/
//--------------------

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    getSchedule();
}
else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    addSchedule();
}
else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    dropSchedule();
}
else{
    $regObj = new \stdClass();
    $regObj->type = 'schedule';
    $regObj->status = 'error';
    $regObj->error = 'wrong request method';

    $respJSON = json_encode($regObj);
    echo $respJSON;
    http_response_code(405); exit();
}

function getSchedule(){
    global $db, $token;

    $stmt = NULL;
    try{
        if(isset($_SERVER['HTTP_SCHEDULE_ID'])){
            $stmt = $db->prepare("SELECT schedule.scheduleID, scheduleentry.row, scheduleentry.row, scheduleentry.monday, scheduleentry.tuesday, scheduleentry.wednesday, scheduleentry.thursday, scheduleentry.friday, scheduleentry.time
                                    FROM schedule
                                    INNER JOIN scheduleentry
                                    ON schedule.scheduleID = scheduleentry.scheduleID
                                    WHERE schedule.userID = ( SELECT userID FROM logintokens WHERE logintokens.token = ?)
                                    AND schedule.scheduleID = ?
                                    ORDER BY schedule.scheduleID ,scheduleentry.row;");
            $stmt->bind_param('si', $token, $_SERVER['HTTP_SCHEDULE_ID']);
        }
        else{
            $stmt = $db->prepare("SELECT schedule.scheduleID, scheduleentry.row, scheduleentry.monday, scheduleentry.tuesday, scheduleentry.wednesday, scheduleentry.thursday, scheduleentry.friday, scheduleentry.time
                                    FROM schedule
                                    INNER JOIN scheduleentry
                                    ON schedule.scheduleID = scheduleentry.scheduleID
                                    WHERE schedule.userID = ( SELECT userID FROM logintokens WHERE logintokens.token = ?)
                                    ORDER BY schedule.scheduleID ,scheduleentry.row;");
            $stmt->bind_param('s', $token);
        }
    $stmt->execute();

    } catch (Exception $e) {
        $regObj = new \stdClass();
        $regObj->type = 'schedule';
        $regObj->status = 'error';
        $regObj->error = 'unknown';
        $regObj->message = 'in entry insert';

        $respJSON = json_encode($regObj);
        echo $respJSON;
        http_response_code(500); exit();   
    }

    $scheduleResult = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    
    $respObj = new \stdClass();
    $respObj->type = 'schedule';

    $schedules = new \stdClass();
    foreach($scheduleResult as &$entry){
        $id = $entry["scheduleID"];
        $row = $entry["row"];

        $days = new \stdClass();
        foreach(['monday','tuesday','wednesday','thursday','friday'] as &$key){
            $days->$key = $entry[$key];
        }

        if(!isset($schedules->$id)) $schedules->$id = [];

        $rowObj = new \stdClass();
        $rowObj->scheduleID = $id;
        $rowObj->row = $row;
        $rowObj->time = $entry["time"];
        $rowObj->days = $days;


        array_push($schedules->$id, $rowObj);
    }

    $respObj->data = $schedules;

    $respJSON = json_encode($respObj);
    echo $respJSON; exit();
}

function addSchedule(){
    global $db, $token;
    //get request json
    $requestBody = trim(file_get_contents("php://input"));
    $requestObj = json_decode($requestBody);

    $title = $requestObj->data->scheduleHeading;
    $units = $requestObj->data->units;

    //insert schedule
    try {
        $stmt = $db->prepare('INSERT INTO schedule (userID, title)
                                VALUES ((SELECT userID FROM logintokens 
                                    WHERE logintokens.token = ?), ?);');
        $stmt->bind_param('ss', $token, $title);
        $stmt->execute();
    } catch (Exception $e) {
        $regObj = new \stdClass();
        $regObj->type = 'schedule';
        $regObj->status = 'error';
        $regObj->error = 'unknown';
        $regObj->message = 'in schedule insert';

        $respJSON = json_encode($regObj);
        echo $respJSON;
        http_response_code(500); exit();   
    }

    $scheduleID = $db->insert_id;

    $sqlArray = [];
    foreach ($units as $row => $unit){
        array_push($sqlArray, $scheduleID);
        array_push($sqlArray, $row);

        foreach(['monday','thuesday','wednesday','thursday','friday'] as &$key){
            if(property_exists($unit, $key)) array_push($sqlArray, $unit->$key->subject->subject);
            else array_push($sqlArray, NULL);
        }
        
        if(property_exists($unit, 'time')) array_push($sqlArray, $unit->time);
        else array_push($sqlArray, NULL);
    }

    //insert schedule entrys
    try {
        $sql = 'INSERT INTO scheduleentry (scheduleID, scheduleentry.row, monday, tuesday, wednesday, thursday, friday, scheduleentry.time)
                    VALUES (?,?,?,?,?,?,?,?)'.str_repeat(",(?,?,?,?,?,?,?,?)",count($units)-1).';';
        $stmt = $db->prepare($sql);
        $stmt->bind_param(str_repeat('iiiiiiis',count($units)), ...$sqlArray);
        $stmt->execute();
    } catch (Exception $e) {
        $regObj = new \stdClass();
        $regObj->type = 'schedule';
        $regObj->status = 'error';
        $regObj->error = 'unknown';
        $regObj->message = 'in entry insert';

        $respJSON = json_encode($regObj);
        echo $respJSON;
        http_response_code(500); exit();   
    }

    if($db -> affected_rows > 0){
        $respObj = new \stdClass();
        $respObj->type = 'schedule';
        $respObj->status = 'done';
        $respObj->addedID = $scheduleID;

        $respJSON = json_encode($respObj);
        echo $respJSON; exit();
    }
    else{
        $regObj = new \stdClass();
        $regObj->type = 'schedule';
        $regObj->status = 'warning';
        $regObj->warning = 'some entrys not added';

        $respJSON = json_encode($regObj);
        echo $respJSON; exit();
    }  
}

function dropSchedule(){
    global $db, $token;
    if(!isset($_SERVER['HTTP_SCHEDULE_ID'])){
        $respObj = new \stdClass();
        $respObj->type = 'schedule';
        $respObj->status = 'error';
        $respObj->error = 'missing header data';
    
        $respJSON = json_encode($respObj);
        echo $respJSON;
        http_response_code(400); exit();
    } 
    
    $scheduleID = $_SERVER['HTTP_SCHEDULE_ID'];

    try {
        $sql = 'DELETE FROM schedule 
                    WHERE userID = ( SELECT userID FROM logintokens 
                        WHERE logintokens.token = ?)
                    AND scheduleID = ?;';
        $stmt = $db->prepare($sql);
        $stmt->bind_param('si', $token, $scheduleID);
        $stmt->execute();
    } catch (Exception $e) {
        $regObj = new \stdClass();
        $regObj->type = 'schedule';
        $regObj->status = 'error';
        $regObj->error = 'unknown';
        $regObj->message = 'in entry insert';

        $respJSON = json_encode($regObj);
        echo $respJSON;
        http_response_code(500); exit();   
    }

    if($db -> affected_rows > 0){
        $regObj = new \stdClass();
        $regObj->type = 'schedule';
        $regObj->status = 'done';

        $respJSON = json_encode($regObj);
        echo $respJSON; exit();
    }
    else{
        $regObj = new \stdClass();
        $regObj->type = 'schedule';
        $regObj->status = 'warning';
        $regObj->status = 'no effect';

        $respJSON = json_encode($regObj);
        echo $respJSON; exit();
    }
}
?>