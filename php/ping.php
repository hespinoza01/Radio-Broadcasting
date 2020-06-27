<?php

date_default_timezone_set("America/New_York");
header("Cache-Control: no-cache");
header("Content-Type: text/event-stream");

//$counter = rand(1, 10);
$curDate = date(DATE_ISO8601);
$time = date('r');
echo "data: La hora del servidor es: {$time}\n\n";

//ob_end_flush();
//flush();
sleep(5);

?>