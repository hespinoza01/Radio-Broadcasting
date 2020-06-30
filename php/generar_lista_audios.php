<?php
require_once 'data.php';
require_once 'data_file.php';
require_once 'getid3/getid3.php';

$data_lista_reproduccion = new ListaReproduccion();
$array_lista_reproduccion = $data_lista_reproduccion->Load()->Get()['lista'];

$filenames = array();
$getID3 = new getID3();
$playfiles = array();

function get_info_files($songs){
    $getID3 = new getID3();
    $playfiles = array();
    $total_playtime = 0;

    foreach ($songs as $song) {
        $id3 = $getID3->analyze(substr($song, 3));
        $playfile = array(
            'filename' => substr($song, 3),
            'filesize' => $id3['filesize'],
            'playtime' => $id3['playtime_seconds'],
            'audiostart' => $id3['avdataoffset'],
            'audioend' => $id3['avdataend'],
            'audiolength' => $id3['avdataend'] - $id3['avdataoffset'],
        );

        $total_playtime += $playfile['playtime'];
        $playfiles[] = $playfile;
    }

    return array("total_playtime" => $total_playtime, "lista" => $playfiles);
}

foreach ($array_lista_reproduccion as $key => $lista) {
    $playfiles[$key] = get_info_files($lista);
}

foreach ($playfiles as $key => $value) {
    print_r($value);
    echo "<br><br>";
}


write_file('../json/reproducir.json', json_encode($playfiles, JSON_PRETTY_PRINT));
?>