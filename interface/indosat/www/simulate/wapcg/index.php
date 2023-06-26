<?php
require_once "/app/xmp_new/0.1/default/config/bootstrap.php";
$config = new config_bootstrap ();
if ($_GET) {
	
	$get = "controller=wapcg";
	$get .= "&adn=43430";
	$get .= "&service=clubfun";
	$get .= "&user=wap_spring";
	$get .= "&pwd=spring1234";
	$get .= "&channel=sms";
	$get .= "&msisdn=" . $_GET ['msisdn'];
	$get .= "&code=" . $_GET ['code'];
	$get .= "&price=" . $_GET ['price'];
	$get .= "&operator=" . $_GET ['operator'];
	
	$url = "http://202.146.224.76:11001/api/index.php?" . $get;

	$result_user = file_get_contents ( $url );
	$val = json_decode ( $result_user, true );
	
	if ($val ['status'] == "OK")
		echo $val ['status'] . "<hr />";
	else
		echo $val ['description'] . "<hr />";
}
?>
<html>
<head>
<title>Simulate Api Wap Charging</title>
</head>
<body>
<form method="get" action="">
<table>
	<tr>
		<td>Msisdn</td>
		<td><input type="text" name="msisdn" /></td>
	</tr>
	<tr>
		<td>Code</td>
		<td><input type="text" name="code" /></td>
	</tr>
	<tr>
		<td>Price</td>
		<td><input type="text" name="price" /></td>
	</tr>
	<tr>
		<td>Operator</td>
		<td><select name="operator">
                <?php
																foreach ( $config->operator as $key => $val ) {
																	foreach ( $val as $operator => $path ) {
																		?>
                <option value="<?php
																		echo $operator?>"><?php
																		echo $operator?></option>
                <?php
																	}
																}
																?>
                </select></td>
	</tr>
	<tr>
		<td></td>
		<td><input type="submit" name="submit" /></td>
	</tr>
</table>
</form>
</body>
</html>
