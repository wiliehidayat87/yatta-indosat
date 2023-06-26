<?php
$lockPath = '/tmp/lock_indosat_hitretry';

if(file_exists($lockPath)) {
        echo "NOK - Lock File Exist on $lockPath \n";
        exit;
} else {
        touch($lockPath);
}

$servername = "172.16.0.62";
$username = "root";
$password = "123456";
$dbname = "xmp";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
$_GET[date]=date("Y-m-d");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT * from tbl_retry where issend = 0 and service = '".$argv[1]."' and adn = '".$argv[2]."' limit 2000";
$result = $conn->query($sql);
//print_r($result);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $hit_url = "http://202.152.162.163:8329/?uid=001504&pwd=y5Hh7ogs&serviceid=".$row['serviceid']."&msisdn=".$row['msisdn']."&sms=".urlencode($row['msgdata'])."&transid=".$row['msgindex']."&smstype=0&sdmcode=".$argv[1]."_".$argv[2];
	//print_r($hit_url);
        $handle = curl_init($hit_url);
        curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($handle, CURLOPT_TIMEOUT, 10);
        $response = "none";
        $response = curl_exec($handle);
	//print_r($response);
        error_log(date('Y-m-d H:i:s')."Hit : ".$hit_url." | Response : ".$response.PHP_EOL, 3, "/tmp/Fix_".$argv[1]."_".$argv[2]."_".$_GET['date'].".log");
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        curl_close($handle);
	$conn->query("update tbl_retry set issend = 1 where id = ".$row['id']);
    }
} else {
    echo "0 results";
}
$conn->close();

unlink($lockPath);
?>
