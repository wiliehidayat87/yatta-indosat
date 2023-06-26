<?php
/*
$url = 'http://'.$_SERVER['SERVER_NAME'].':29790/indosat/mo/index.php';
if ($_POST) {
    require_once "/app/xmp2012/interface/indosat/xmp.php";
    
    $param = '<message type="mo">
      <adn>' . $_POST['adn'] . '</adn>
      <msisdn>' . $_POST['msisdn'] . '</msisdn>
      <trx_id>' . $_POST['trx_id'] . '</tid>
      <sms>' . $_POST['sms'] . '</sms>
      <trx_date>' . $_POST['trx_date'] . '</tdate>
      </message>';

    echo http_request::post($url, $_POST);
    echo '<hr />';
}
*/
?>
<!-- form method="post" action="<?php echo $url;?>" -->
<form method="get" action="../../mo/index.php">
    <table>
        <tr>
            <td>MSISDN</td>
            <td><input name="msisdn" type="text" /></td>
        </tr>
        <tr>
            <td>MSG</td>
            <td><input name="sms" type="text" /></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><input name="submit" type="submit" /></td>
        </tr>
    </table>
    <input name="trx_time" value="<?= date("Ymdhis"); ?>" type="hidden" />
    <input name="transid" value="<?= mt_rand(10000000, 9999999) ?>" type="hidden" />
    <input name="substype" value="<?= array_rand(array(10, 20, 30)) ?>" type="hidden" />
    <input name="sdc" value="9879" type="hidden" />
</form>
