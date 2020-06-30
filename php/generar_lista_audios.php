<?php
require_once 'data.php';
require_once 'data_file.php';
require_once 'getid3/getid3.php';

$data_lista_reproduccion = new ListaReproduccion();
$array_lista_reproduccion = $data_lista_reproduccion->Load()->Get();

$filenames = array();
$getID3 = new getID3();
$playfiles = array();

function get_extension_file($value){
    $explode = explode('.', $value);
    return array_slice($explode, -1)[0];
}

foreach ($array_lista_reproduccion['lista'] as $key => $value) {
    $filenames[] = array_filter($value['lista'], function($item) { 
        return in_array(get_extension_file($item), ['mp3','ogg']); 
    });
}

foreach ($filenames as $key => $filenames_item) {
    foreach ($filenames_item as $filename) {
        $id3 = $getID3->analyze(substr($filename, 3));
        //if ($id3['fileformat'] == 'mp3') {
        $playfile = array(
            'filename' => substr($filename, 3),
            'filesize' => $id3['filesize'],
            'playtime' => $id3['playtime_seconds'],
            'audiostart' => $id3['avdataoffset'],
            'audioend' => $id3['avdataend'],
            'audiolength' => $id3['avdataend'] - $id3['avdataoffset'],
        );

        $playfiles[] = $playfile;
    }
}

foreach ($playfiles as $key => $value) {
    print_r($value);
    echo "<br><br>";
}


write_file('../json/reproducir.json', json_encode($playfiles, JSON_PRETTY_PRINT));
?>