<?php
$dbconn = mysqli_connect("172.16.0.62", "root", "123456");
mysqli_select_db($dbconn,"xmp");
			
$msisdn = trim($_GET['msisdn']);
$msisdn = htmlentities($msisdn, ENT_QUOTES);
$msisdn = mysqli_real_escape_string($dbconn, $msisdn);

$service = trim($_GET['service']);
$service = htmlentities($service, ENT_QUOTES);
$service = mysqli_real_escape_string($dbconn, $service);

$sql = "select * from subscription where msisdn = '".$msisdn."' and active = '1' and service = '".$service."' ORDER BY id DESC LIMIT 1;";
$query = mysqli_query($dbconn, $sql);
$row = mysqli_fetch_array($query,MYSQLI_ASSOC);
mysqli_free_result($query);
mysqli_close($dbconn);

if(count($row) > 0)
{
	echo json_encode($row);
}
else
{
	echo "NOK";
}
?>
