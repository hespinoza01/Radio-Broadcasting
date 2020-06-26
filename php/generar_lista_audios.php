<?php
require_once 'data.php';

$ruta = AUDIOS_RUTA;
$list_dirs = array();

if ($directory = opendir($ruta)) { 
    $dirs = scandir($ruta);

    $list_dirs = array_filter($dirs, function($item) use($ruta){ return is_dir($ruta.$item) && !in_array($item, ['.','..']); });

    asort($list_dirs);

    /*foreach ($list_dirs as $dir) {
        //solo si el archivo es un directorio, distinto que "." y ".."
        $val64 = explode("_",$dir);
        if($val64[0]!="fonts" && $val64[0]!="images" && $val64[0]!="js"&& $val64[0]!="AUDIOS"&& $val64[0]!="css"&& $val64[0]!="imagenes") {
            echo "<option value=\"$dir\">$dir</option>";
            }
      }   */
  
    closedir($dh);
}

print_r($list_dirs);

echo "<hr>";

$a = 4;
$b = 10;

[$a, $b] = [$b, $a];

echo $a." => ".$b;

?>