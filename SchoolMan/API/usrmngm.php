<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
}
else{
    //echo "Error 405: worng Request Method";
    http_response_code(405); exit();
}
?>