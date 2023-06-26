<?php

$queue  = '/topic/foo';
$msg    = 'bar';
print_r($argv);
if ($argv[1] != NULL) {
   $msg = $argv[1];
}

try {
    $stomp = new Stomp('tcp://127.0.0.1:61613');
    while (true) {
      $stomp->send($queue, $msg." ". date("Y-m-d H:i:s"));
      sleep(1);
    }
} catch(StompException $e) {
    die('Connection failed: ' . $e->getMessage());
}

?>

