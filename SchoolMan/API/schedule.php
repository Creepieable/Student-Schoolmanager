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
    if(isset($_SERVER['HTTP_SCHEDULE_ID'])){
        //select specific
    }
    else{
        //select all schedules
    }

}

function addSchedule(){
    global $db, $token;
    //get request json
    $requestBody = trim(file_get_contents("php://input"));
    $requestObj = json_decode($requestBody);

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
    
}
?>