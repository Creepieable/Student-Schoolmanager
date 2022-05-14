<?php
$dbHost = 'localhost';
$dbUsr = 'root';
$dbPw = '';
$dbName = 'schoolman';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if($_SERVER['HTTP_USR_MGM_TYPE'] === 'register'){
        header('Content-Type: application/json; charset=utf-8');
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
        header('Content-Type: application/json; charset=utf-8');
        $json = trim(file_get_contents("php://input"));
        $data = json_decode($json);
        login($data);
    }
    else if($_SERVER['HTTP_USR_MGM_TYPE'] === 'token'){
        header('Content-Type: application/json; charset=utf-8');
        $json = trim(file_get_contents("php://input"));
        $data = json_decode($json);
        tokenAvail($data);
    }
    else{
        //echo "Error 400: bad request";
        http_response_code(400); exit();
    }
}
else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if($_SERVER['HTTP_USR_MGM_TYPE'] === 'logout'){
        $token = $_SERVER['HTTP_USR_TOKEN'];
        logout($token);
    }
    else{
        //echo "Error 400: bad request";
        http_response_code(400); exit();
    }
}
else{
    echo "Error 405: worng request method";
    http_response_code(405); exit();
}

function register($POSTjson){
    global $dbHost, $dbUsr, $dbPw, $dbName;
    $db = new mysqli($dbHost, $dbUsr, $dbPw, $dbName);
    if ($db -> connect_errno) {
        $regObj = new \stdClass();
            $regObj->type = 'register';
            $regObj->status = 'error';
            $regObj->error = 'server database error';
        
            $respJSON = json_encode($regObj);
            echo $respJSON;
            http_response_code(500); exit(); 
    }
    try {
    $stmt = $db->prepare('INSERT INTO users (name, email, passwd, salt) VALUES (?, ?, ?, ?);');
    $username = strtolower(strval($POSTjson->data->name));
    $email = strtolower(strval($POSTjson->data->email));
    $password = strval($POSTjson->data->saltedPasswordHash);
    $salt = strval($POSTjson->data->salt);

    $emailPattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';
    $namePattern = '/^(?!.*\.\.)(?!.*\.$)[^\W][\w.]{0,29}$/';

    //server sided regex check
    if(!(preg_match($emailPattern ,$email))){
        $regObj = new \stdClass();
        $regObj->type = 'register';
        $regObj->status = 'error';
        $regObj->error = 'bad email format';
    
        $respJSON = json_encode($regObj);
        echo $respJSON;
        http_response_code(406); exit();
    }
    if(!(preg_match($namePattern, $username))){
        $regObj = new \stdClass();
        $regObj->type = 'register';
        $regObj->status = 'error';
        $regObj->error = 'bad name format';
    
        $respJSON = json_encode($regObj);
        echo $respJSON;
        http_response_code(406); exit();
    }

    $stmt->bind_param('ssss', $username, $email, $password, $salt);

    $stmt->execute();
    } catch (Exception $e) {
        if($e->getCode() === 1062){
            $regObj = new \stdClass();
            $regObj->type = 'register';
            $regObj->status = 'error';
            $regObj->error = 'already exists';
        
            $respJSON = json_encode($regObj);
            echo $respJSON;
            http_response_code(403); exit();
        }
        else{
            $regObj = new \stdClass();
            $regObj->type = 'register';
            $regObj->status = 'error';
            $regObj->error = 'unknown';
        
            $respJSON = json_encode($regObj);
            echo $respJSON;
            http_response_code(500); exit();   
        }
    }

    $regObj = new \stdClass();
    $regObj->type = 'register';
    $regObj->status = 'done';

    $respJSON = json_encode($regObj);
    echo $respJSON; exit();
}

function gequestSalt($POSTjson){
    global $dbHost, $dbUsr, $dbPw, $dbName;
    $db = new mysqli($dbHost, $dbUsr, $dbPw, $dbName);
    if ($db -> connect_errno) {
        $regObj = new \stdClass();
        $regObj->type = 'login';
        $regObj->status = 'error';
        $regObj->error = 'server database error';
    
        $respJSON = json_encode($regObj);
        echo $respJSON;
        http_response_code(500);
        exit();
    }
    $stmt = $db->prepare('SELECT salt FROM users WHERE name=? OR email=?;');
    $user = strtolower(strval($POSTjson->data->name));
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
    if($usersFound == 0){
        $regObj = new \stdClass();
        $regObj->type = 'salt';
        $regObj->status = 'error';
        $regObj->error = 'user not found';
    
        $respJSON = json_encode($regObj);
        echo $respJSON;
        http_response_code(404); exit();   
    }
    else{
        $regObj = new \stdClass();
        $regObj->type = 'salt';
        $regObj->status = 'error';
        $regObj->error = 'unknown';
    
        $respJSON = json_encode($regObj);
        echo $respJSON;
        http_response_code(500); exit();
    }
}

function login($POSTjson){
    global $dbHost, $dbUsr, $dbPw, $dbName;
    $db = new mysqli($dbHost, $dbUsr, $dbPw, $dbName);
    if ($db -> connect_errno) {
        $regObj = new \stdClass();
        $regObj->type = 'login';
        $regObj->status = 'error';
        $regObj->error = 'server database error';
    
        $respJSON = json_encode($regObj);
        echo $respJSON;
        http_response_code(500); exit(); 
    }
    $stmt = $db->prepare('SELECT userID FROM users WHERE (name=? OR email=?) AND passwd=?;');
    $user = strtolower(strval($POSTjson->data->name));
    $passwd = strval($POSTjson->data->password);

    $stmt->bind_param('sss', $user, $user, $passwd);

    $stmt->execute();
    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $usersFound = count($result);
    if($usersFound == 1){
        $userID = $result[0]["userID"];

        $regObj = new \stdClass();
        $regObj->type = 'login';
        $regObj->status = 'done';
        
        //generate a 32bit logintoken
        $token = bin2hex(random_bytes(32));
        
        //insert generated token into databade
        $stmt = $db->prepare('INSERT INTO logintokens (token, userID) VALUES (?, ?); ');
        $stmt->bind_param('si', $token, $userID);
        $stmt->execute();

        $data = new \stdClass();
        $data->token = $token;

        $regObj->data = $data;

        $respJSON = json_encode($regObj);
        echo $respJSON; exit();
    }
    if($usersFound == 0){
        $regObj = new \stdClass();
        $regObj->type = 'login';
        $regObj->status = 'error';
        $regObj->error = 'no user match';
    
        $respJSON = json_encode($regObj);
        echo $respJSON;
        http_response_code(404); exit();   
    }
    else{
        $regObj = new \stdClass();
        $regObj->type = 'login';
        $regObj->status = 'error';
        $regObj->error = 'unknown error';
    
        $respJSON = json_encode($regObj);
        echo $respJSON;
        http_response_code(500); exit();
    }
}

function logout($token){
    global $dbHost, $dbUsr, $dbPw, $dbName;
    $db = new mysqli($dbHost, $dbUsr, $dbPw, $dbName);
    if ($db -> connect_errno) {
        $regObj = new \stdClass();
        $regObj->type = 'logout';
        $regObj->status = 'error';
        $regObj->error = 'server database error';
    
        $respJSON = json_encode($regObj);
        echo $respJSON;
        http_response_code(500); exit(); 
    }   
    try{
        $stmt = $db->prepare('DELETE FROM logintokens WHERE token=?;');
        $stmt->bind_param('s', $token);
        $stmt->execute();

    } catch (Exception $e) {
        $regObj = new \stdClass();
        $regObj->type = 'logout';
        $regObj->status = 'error';
        $regObj->error = 'unknown';
    
        $respJSON = json_encode($regObj);
        echo $respJSON;
        http_response_code(500); exit();   

    }
    echo "done"; exit();
}

function tokenAvail($POSTjson){
    global $dbHost, $dbUsr, $dbPw, $dbName;
    $db = new mysqli($dbHost, $dbUsr, $dbPw, $dbName);
    if ($db -> connect_errno) {
        $regObj = new \stdClass();
        $regObj->type = 'logout';
        $regObj->status = 'error';
        $regObj->error = 'server database error';
    
        $respJSON = json_encode($regObj);
        echo $respJSON;
        http_response_code(500); exit();
    }
    $stmt = $db->prepare('SELECT userID, UNIX_TIMESTAMP(created) AS created, expiresIn FROM logintokens WHERE token=?;');

    //get token from JSON
    $token = strval($POSTjson->data->token);

    $stmt->bind_param('s', $token);

    $stmt->execute();

    $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    //get current Timestamp from database server
    $stmt = $db->prepare('SELECT UNIX_TIMESTAMP(CURRENT_TIMESTAMP) AS _current_time;');
    $stmt->execute();
    $current = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    $usersFound = count($result);
    if($usersFound == 1){
        $currentTime = $current[0]['_current_time'];
        $creationTime = $result[0]['created'];
        $expIn = $result[0]['expiresIn'];

        //if token expired
        if($expIn !== null && $currentTime - $creationTime >= $expIn){
            //DELETE expired token in database
            $stmt = $db->prepare('DELETE FROM logintokens WHERE token=?;');
            $stmt->bind_param('s', $token);
            $stmt->execute();

            $regObj = new \stdClass();
            $regObj->type = 'tokenAvail';
            $regObj->status = 'error';
            $regObj->error = 'expired';
        
            $respJSON = json_encode($regObj);
            echo $respJSON;
            http_response_code(410); exit();
        }
        else{
            $regObj = new \stdClass();
            $regObj->type = 'token';
            $regObj->status = 'avail';

            $respJSON = json_encode($regObj);
            echo $respJSON; exit();
        }
    }
    else if($usersFound == 0){
        $regObj = new \stdClass();
        $regObj->type = 'tokenAvail';
        $regObj->status = 'error';
        $regObj->error = 'missing';
    
        $respJSON = json_encode($regObj);
        echo $respJSON;
        http_response_code(404); exit();
    }
    else{
        $regObj = new \stdClass();
        $regObj->type = 'tokenAvail';
        $regObj->status = 'error';
        $regObj->error = 'unknown';
    
        $respJSON = json_encode($regObj);
        echo $respJSON;
        http_response_code(500); exit();
    }
}
?>