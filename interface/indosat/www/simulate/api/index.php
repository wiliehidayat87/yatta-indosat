<?php 
if($_POST){
	require_once '/app/operator/tim/0.1/tim/xmp.php';
	$param = '<subscription_request operation="notification">
			<channel>PIADA</channel>
			<target_list>
				<target action="'.$_POST['status'].'" event_source="SMS">'.$_POST['phone'].'</target>
			</target_list>
		</subscription_request>';
	$url = 'http://10.1.2.14:11001/api/tim/index.php';
	$hit = http_request::post ( $url, $param );
	if($hit){
		echo '<hr />'.$hit.'<hr />';
	}else{
		echo '<hr />Not Responding<hr />';
	}
}
?>
<form method="post" action="">
<table>
        <tr>
                <td>MSISDN</td>
                <td><input name="phone" type="text" /></td>
        </tr>
        <tr>
                <td>STATUS</td>
                <td>
                <input type="radio" name="status" value=SUBSCRIBE /> Subscribe<br />
                <input type="radio" name="status" value=UNSUBSCRIBE /> Unsubscribe<br />
                </td>
        </tr>
        <tr>
                <td>&nbsp;</td>
                <td><input name="submit" type="submit" /></td>
        </tr>
</table>
</form>
