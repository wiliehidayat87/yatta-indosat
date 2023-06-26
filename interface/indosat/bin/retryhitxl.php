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
$sql = "SELECT * from tbl_retry where issend = 0 and service = '".$argv[1]."' and adn = '".$argv[2]."' limit 1000";
$result = $conn->query($sql);
//print_r($result);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        
		$hit_url = "http://127.0.0.1:44023/cgi-bin/sendsms?username=mp&password=esemesmp&smsc=XLMTsdp&from=".$row['serviceid']."&to=".$row['msisdn']."&text=".urlencode($row['msgdata'])."&meta-data=%3Fsmpp%3FShortname%3D0018202000013546%26&dlr-mask=31&dlr-url=http%3A%2F%2F172.16.0.229%3A9899%2Fdrsdp%2Findex.php%3Ftxid%3D".$row['msgindex']."%26status%3D%25d%26answer%3D%25A%26ccode%3D%25P%26msisdn%3D%25p%26ts%3D%25t%26adn%3D99879%26meta-data%3D%25D%26mdlr%3D%25B%26ErrorCode%3D%25B%26ErrorSource%3D%25B";
		//$hit_url = "http://114.4.135.22:8329/?uid=001504&pwd=y5Hh7ogs&serviceid=".$row['serviceid']."&msisdn=".$row['msisdn']."&sms=".urlencode($row['msgdata'])."&transid=".$row['msgindex']."&smstype=0&sdmcode=".$argv[1]."_".$argv[2];
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

