<?php

require_once 'data_file.php';

if(!isset($_GET['index'])){
    http_response_code(404);
    die();
}

$play_list = read_file('../json/reproducir.json');
$index = $_GET['index'];

$index = ($index < count($play_list)-1) ? $index+1 : 0;

header("Content-Type: application/json");

echo json_encode(array(
    'playlist_index'    => $index,
    'song_index'        => 0
));

?>