<?php
$filename = "/app/xmp2012/logs/indosat/game_setting/setting_content_gameasik.txt";

$mysqli = new mysqli('172.16.0.62', 'root', '123456', 'dbpush');

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$query = "SELECT content FROM push_content WHERE service='asik' ORDER BY modified DESC LIMIT 1";
$content_push="";
if ($stmt = $mysqli->prepare($query)) {

    /* execute statement */
    $stmt->execute();

    /* bind result variables */
    $stmt->bind_result($content);

    /* fetch values */
    while ($stmt->fetch()) {
        $content_push = $content;
    }

    /* close statement */
    $stmt->close();
}

/* close connection */
$mysqli->close();

$write = fopen($filename, "w");
fwrite($write, serialize($content_push));
fclose($write);