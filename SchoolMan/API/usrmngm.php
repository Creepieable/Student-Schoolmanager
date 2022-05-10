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
    else if($_SERVER['HTTP_USR_MGM_TYPE'] === 'login'){
        //header('Content-Type: application/json; charset=utf-8');
        $json = trim(file_get_contents("php://input"));
        $data = json_decode($json);
        login($data);
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
        exit();
    }
    $stmt = $db->prepare('INSERT INTO users (name, email, passwd, passwd)
                          VALUES (?, ?, ?, ?);');
    $username = strval($POSTjson->data->name);
    $email = strval($POSTjson->data->email);
    $password = strval($POSTjson->data->saltedPasswordHash);
    $salt = strval($POSTjson->data->salt);
    $stmt->bind_param("ssss", $username, $email, $password, $salt);

    //$stmt->execute();

    $regObj = new \stdClass();
    $regObj->type = 'register';
    $regObj->status = 'done';

    $respJSON = json_encode($regObj);
    echo $respJSON;
}

function login($POSTjson){

}
?>