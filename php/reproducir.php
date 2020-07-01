<?php
require_once 'data_file.php';
require_once 'data.php';

$data_lista_reproduccion = new ListaReproduccion();

$start_time = (int)$data_lista_reproduccion->Load()->Get()['time'];
$current_time = time();
$play_list = read_file('../json/reproducir.json');
$total_playtime = 0;
$play_sum = 0;
$i = 0; // indice de la ronda a reproducir
$j = 0; // indice de la cancion dentro de la ronda a reproducir

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
header("Cache-Control: no-cache");
header("Content-Transfer-Encoding: binary");
//header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
//header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
//header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
//header("Cache-Control: post-check=0, pre-check=0", false);
//header("Pragma: no-cache");
//header('Content-Length: '.$settings['max_listen_time'] * $settings['bitrate'] * 128); //suppreses chuncked transfer-encoding
//echo json_encode($play_list, JSON_PRETTY_PRINT);
function get_audio_stream($path){
    $path = str_replace("\\", "", $path);
    return file_get_contents($path); 
}

//sum playtime
foreach ($play_list as $index => $playfiles) {
    $i = $index;
    $j = 0;
    $stop = false;
    $play_sum += $playfiles['total_playtime'];

    if($current_time-$start_time > $play_sum){
        $total_playtime += $playfiles['total_playtime'];
        continue;
    }

    foreach($playfiles['lista'] as $key => $playfile){
        $total_playtime += $playfile['playtime'];
        $j = $key;
        if($total_playtime >= $current_time-$start_time){
            $stop = true;
            break; 
        }
    }

    if($stop)  break;
}

$current_song = $play_list[$i]['lista'][$j];
write_file('song.log', $current_song['filename'].", $i, $j");

/*$play_current_time = ($total_playtime - ($current_time-$start_time));
//$track_pos = ($current_song['playtime'] - $total_playtime) * $current_song['audiolength'] / $current_song['playtime'];
$track_pos = ($play_current_time * $current_song['audiolength']) / $current_song['playtime'];
$old_buffer = substr(get_audio_stream($current_song['filename']), $track_pos);

echo $old_buffer; die();*/
set_time_limit(0);
if($_SERVER['REQUEST_METHOD'] == 'GET'){
    echo get_audio_stream($current_song['filename']);
    flush();
}else{
    $res = array(
        'filename' => $current_song['filename'],
        'current_time' => $current_song['playtime'] - ($total_playtime - ($current_time-$start_time)),
        'duration_time' => $current_song['playtime']
    );

    echo json_encode($res, JSON_PRETTY_PRINT);
}

?>