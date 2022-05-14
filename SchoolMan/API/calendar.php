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
    //get request json
    $requestBody = trim(file_get_contents("php://input"));
    $requestObj = json_decode($requestBody);

    
}  

function getCalendarEntrys(){
    if(!isset($_SERVER['HTTP_CALENDAR_FROM_STAMP']) || !isset($_SERVER['HTTP_CALENDAR_TO_STAMP'])){
        $respObj = new \stdClass();
        $respObj->type = 'calendar';
        $respObj->status = 'error';
        $respObj->error = 'missing header data';
    
        $respJSON = json_encode($respObj);
        echo $respJSON;
        http_response_code(400); exit();
    }
    $fromDate = $_SERVER['HTTP_CALENDAR_FROM_STAMP'];
    $toDate = $_SERVER['HTTP_CALENDAR_TO_STAMP'];

    //setup DB connection
    global $db, $token;
    
    /*SELECT taskID, title, UNIX_TIMESTAMP(dueBy) AS dueBy, isTimed, colour FROM tasks 
	INNER JOIN logintokens ON tasks.userID = logintokens.userID 
	WHERE logintokens.token = "6dccf1e1d227c69fbdf816647e7755527a0f8cfb2f1dff4d311ee1720a3f4265"
			AND UNIX_TIMESTAMP(dueBy) > 0
			AND UNIX_TIMESTAMP(dueBy) < 2652555455;*/
}

function dropCalendarEntry(){
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
    
    global $db, $token;

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
        $regObj->status = 'noChange';

        $respJSON = json_encode($regObj);
        echo $respJSON; exit();
    }
}
?>