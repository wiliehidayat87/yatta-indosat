<form method="post">
	<input type="hidden" name="time" value="<?php echo date("YmdHis")?>" />
	<input type="hidden" name="adn" value="7277"/>
	<table border="0" cellpadding="2" cellspacing="2">
		<tr><td>tid</td><td>:</td><td><input type="text" name="tid"/></td></tr>
		<tr><td>service id</td><td>:</td><td><input type="text" name="serviceid"/></td></tr>
		<tr><td>destination</td><td>:</td><td><input type="text" name="dest"/></td></tr>
		<tr><td></td><td></td><td><input name="submit" type="submit" value="submit"/></Td></tr>
	</table>
</form>


<?php
if(isset($_POST['submit']))
{
	$time = $_POST['time'];
	$serviceid = $_POST['serviceid'];
	$tid = $_POST['tid'];
	$dest = $_POST['dest'];
	$adn = $_POST['adn'];
	print_r($_POST);
	$url = "http://127.0.0.1:20002/dr/index.php?adn=$adn&time=$time&serviceid=$serviceid&tid=$tid&dest=$dest";
	header("location:$url");
}

?>
