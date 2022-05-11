<?php
$dbHost = 'localhost';
$dbUsr = 'root';
$dbPw = '';
$dbName = 'schoolman';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if($_SERVER['HTTP_USR_MGM_TYPE'] === 'register'){
        //header('Content-Type: application/json; charset=utf-8');
        $json = trim(file_get_contents("php://input"));
        $data = json_decode($json);
        register($data);
    }
    else if($_SERVER['HTTP_USR_MGM_TYPE'] === 'salt'){
        header('Content-Type: application/json; charset=utf-8');
        $json = trim(file_get_contents("php://input"));
        $data = json_decode($json);
        gequestSalt($data);
    }
    else if($_SERVER['HTTP_USR_MGM_TYPE'] === 'login'){
        //header('Content-Type: application/json; charset=utf-8');
        $json = trim(file_get_contents("php://input"));
        $data = json_decode($json);
        login($data);
    }
    else if($_SERVER['HTTP_USR_MGM_TYPE'] === 'token'){
        //header('Content-Type: application/json; charset=utf-8');
        $json = trim(file_get_contents("php://input"));
        $data = json_decode($json);
        tokenAvail($data);
    }
    else{
        //echo "Error 400: bad request";
        http_response_code(400); exit();
    }
}
else{
    //echo "Error 405: worng request method";
    http_response_code(405); exit();
}

function register($POSTjson){
    global $dbHost, $dbUsr, $dbPw, $dbName;
    $db = new mysqli($dbHost, $dbUsr, $dbPw, $dbName);
    if ($db -> connect_errno) {
        echo "Failed to connect to MySQL: " . $db -> connect_error;
        http_response_code(500);
        exit();
    }
    $stmt = $db->prepare('INSERT INTO users (name, email, passwd, passwd)
                          VALUES (?, ?, ?, ?);');
    $username = strval($POSTjson->data->name);
    $email = strval($POSTjson->data->email);
    $password = strval($POSTjson->data->saltedPasswordHash);
    $salt = strval($POSTjson->data->salt);
    $stmt->bind_param('ssss', $username, $email, $password, $salt);

    //$stmt->execute();

    $regObj = new \stdClass();
    $regObj->type = 'register';
    $regObj->status = 'done';

    $respJSON = json_encode($regObj);
    echo $respJSON;
}

function login($POSTjson){
    global $dbHost, $dbUsr, $dbPw, $dbName;
    $db = new mysqli($dbHost, $dbUsr, $dbPw, $dbName);
    if ($db -> connect_errno) {
        echo "Failed to connect to MySQL: " . $db -> connect_error;
        http_response_code(500);
        exit();
    }
    $stmt = $db->prepare('SELECT userID FROM users WHERE (name=? OR email=?) AND passwd=?;');
    $user = strval($POSTjson->data->user);
    $passwd = strval($POSTjson->data->password);

    $stmt->bind_param('sss', $user, $user, $passwd);

    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $userID = $result[0]["userID"];

    $usersFound = count($result);
    if($usersFound == 1){
        $regObj = new \stdClass();
        $regObj->type = 'login';
        $regObj->status = 'done';
        
        $token = bin2hex(random_bytes(32));
        tokenToDB($userID, $token);

        $data = new \stdClass();
        $data->user = $user;
        $data->token = $token;

        $regObj->data = $data;

        $respJSON = json_encode($regObj);
        echo $respJSON;
        exit();
    }
    if($$usersFound == 0){
        http_response_code(404); exit();   
    }
    else{
        http_response_code(500); exit();
    }
}

function tokenToDB($userID, $token){
    global $dbHost, $dbUsr, $dbPw, $dbName;
    $db = new mysqli($dbHost, $dbUsr, $dbPw, $dbName);
    if ($db -> connect_errno) {
        echo "Failed to connect to MySQL: " . $db -> connect_error;
        http_response_code(500);
        exit();
    }
    $stmt = $db->prepare('INSERT INTO logintokens (token, userID) VALUES (?, ?); ');
    $stmt->bind_param('si', $token, $userID);

    $stmt->execute();
}

function gequestSalt($POSTjson){
    global $dbHost, $dbUsr, $dbPw, $dbName;
    $db = new mysqli($dbHost, $dbUsr, $dbPw, $dbName);
    if ($db -> connect_errno) {
        echo "Failed to connect to MySQL: " . $db -> connect_error;
        http_response_code(500);
        exit();
    }
    $stmt = $db->prepare('SELECT salt FROM users WHERE name=? OR email=?;');
    $user = strval($POSTjson->data->name);
    $stmt->bind_param('ss', $user, $user);

    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $usersFound = count($result);
    if($usersFound == 1){
        $regObj = new \stdClass();
        $regObj->type = 'salt';
        $regObj->status = 'done';
        
        $data = new \stdClass();
        $data->salt = $result[0]["salt"];

        $regObj->data = $data;

        $respJSON = json_encode($regObj);
        echo $respJSON;
        exit();
    }
    if($$usersFound == 0){
        http_response_code(404); exit();   
    }
    else{
        http_response_code(500); exit();
    }
}

function tokenAvail($POSTjson){
    global $dbHost, $dbUsr, $dbPw, $dbName;
    $db = new mysqli($dbHost, $dbUsr, $dbPw, $dbName);
    if ($db -> connect_errno) {
        echo "Failed to connect to MySQL: " . $db -> connect_error;
        http_response_code(500);
        exit();
    }
    $stmt = $db->prepare('SELECT userID FROM logintokens WHERE token=?;');
    $userToken = strval($POSTjson->data->token);

    $stmt->bind_param('s', $userToken);

    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $userID = $result[0]["userID"];

    $usersFound = count($result);

    if($usersFound == 1){
        $regObj = new \stdClass();
        $regObj->type = 'token';
        $regObj->status = 'avail';

        $respJSON = json_encode($regObj);
        echo $respJSON;
        exit();
    }
    if($$usersFound == 0){
        http_response_code(404); exit();
    }
    else{
        http_response_code(500); exit();
    }
}
?>