<?php
$dbHost = 'localhost';
$dbUsr = 'root';
$dbPw = '';
$dbName = 'schoolman';

$db = new mysqli($dbHost, $dbUsr, $dbPw, $dbName);
if ($db -> connect_errno) {
    echo "Failed to connect to MySQL: " . $db -> connect_error;
    http_response_code(500);
    exit();
}

$stmt = $db->prepare('SELECT userID, UNIX_TIMESTAMP(created) AS created, expiresIn FROM logintokens WHERE token=?;');
$userToken = "eca6a7aaff699040120d82560defd54ce6e31ca886d96cdd586fb940c7738653";

$stmt->bind_param('s', $userToken);

$stmt->execute();
$result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$stmt = $db->prepare('SELECT UNIX_TIMESTAMP(CURRENT_TIMESTAMP) AS _current_time;');
$stmt->execute();
$current = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$currentTime = $current[0]['_current_time'];
$creationTime = $result[0]['created'];
$expIn = $result[0]['expiresIn'];

echo 'Created: '.$creationTime.'<br>';
echo 'Current: '.$currentTime.'<br>';
echo 'Expires: '.$expIn.' s<br>';

echo 'dTime: ';
echo $currentTime - $creationTime;
echo '<br>';

if($currentTime - $creationTime >= $expIn) 
{
    $stmt = $db->prepare('DELETE FROM logintokens WHERE token=?;');
    $stmt->bind_param('s', $userToken);
    $stmt->execute();
    echo 'expired';
}
else 
{
    echo 'valid';
}
?>