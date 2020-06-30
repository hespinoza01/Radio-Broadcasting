<?php
require_once 'data_file.php';
require_once 'data.php';

$data_lista_reproduccion = new ListaReproduccion();

$start_time = (int)$data_lista_reproduccion->Load()->Get()['time'];
$currrent_time = time();
$playfiles = read_file('../json/reproducir.json');
$total_playtime = 0;
$play_sum = 0;
$i = 0;

$settings = array(
    'name' => 'ElectroAddict',
    'genre' => 'Electronic',
    'url' => $_SERVER['HTTP_HOST'],
    'bitrate' => 160,
    'buffer_size' => 16384,
    'max_listen_time' => 14400,
    'randomize_seed' => 31337,
);

//output headers
//header("Content-Type: application/json");
header('Content-type: audio/mpeg');
header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
//header('Content-Length: '.$settings['max_listen_time'] * $settings['bitrate'] * 128); //suppreses chuncked transfer-encoding

function get_audio_stream($path){
    $path = str_replace("\\", "", $path);
    return file_get_contents($path); 
}

//sum playtime
foreach ($playfiles as $index=>$playfile) {
    $i = $index;
    $total_playtime += $playfile['playtime'];
    if($total_playtime >= $currrent_time-$start_time){
        break;
    }
}

echo get_audio_stream($playfiles[$i]['filename']);
flush();

?>