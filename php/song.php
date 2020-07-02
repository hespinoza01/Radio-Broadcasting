<?php

$music;

if(isset($_GET['path'])){
    //$song = base64_encode(file_get_contents($_GET['path']));
    $song = file_get_contents($_GET['path']);
    $music = 'data:audio/mp3;base64,'.$song;
}

header ("Content-Type: audio/mpeg");
//header ("Content-Transfer-Encoding: binary");
//header ("Pragma: no-cache");

file_put_contents('song.log', $song);
echo $song;
//echo $music;

?>