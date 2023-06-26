<?php
if($_GET['WAP'] == "eagame"){
	$filename = "/app/xmp2012/logs/indosat/game_setting/setting_eagame.txt";
}else{
	$filename = "/app/xmp2012/logs/indosat/game_setting/setting_gameasik.txt";
}

$setting['REG'] = $_GET['REG'];
$setting['PULL'] = $_GET['PULL'];

$write = fopen($filename, "w");
fwrite($write, serialize($setting));
fclose($write);