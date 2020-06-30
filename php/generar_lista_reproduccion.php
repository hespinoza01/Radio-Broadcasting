<?php

require_once 'mezclar/mezcla_lista.php';
require_once 'data.php';
require_once 'getid3/getid3.php';

$datos_generos = new Generos();
$datos_comerciales = new Comerciales();
$datos_generos_A_P = new GenerosAP();
$datos_variables = new General();

$datos_generos->Load();
$datos_comerciales->Load();
$datos_generos_A_P->Load();
$datos_variables->Load();

$lista = array(); //lista a crear
$generos = $datos_generos->Get(); //se carga la lista de generos
$comerciales = $datos_comerciales->Get(); // lista de comerciales
$generos_A_P = $datos_generos_A_P->Get()["generos_A_P"]; // lista de los generos_a_p

$lista_reproducciones = array();
$current_lista = 0; //cargar
$nronda = $datos_variables->Get()["nronda"]; //cargar
$cont_A_P = $datos_variables->Get()["cont_A_P"]; //cargar 
$RANDOM = $datos_variables->Get()["RANDOM"]; // valor de random
$SEPARAR_GENERO = $datos_variables->Get()["SEPARAR_GENERO"];
$activar_permutacion = false; //cargar 
$permutacion = array(); //cargar
$permutado_pasado = array(); //cargar 
$conta = $datos_variables->Get()["conta"]; //cargar
$iniciar_R_2 = $datos_variables->Get()["iniciar_R_2"]; //cargar
$escalar = $datos_variables->Get()["escalar"]; //cargar
$comerciales_generos = false; //cargar;
$pasado = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $RANDOM = $_POST["RANDOM"];
    $nronda = $_POST["nronda"];
    $SEPARAR_GENERO = $_POST["SEPARAR_GENERO"];
}

if($RANDOM == 3){
    $permutacion = $datos_variables->Get()["permutacion"];
    $permutado_pasado = $datos_variables->Get()["permutado_pasado"];
    $activar_permutacion = $datos_variables->Get()["activar_permutacion"];
}

function get_extension_file($value){
    $explode = explode('.', $value);
    return array_slice($explode, -1)[0];
}

for($i=$current_lista, $j=0; $i<((int)($nronda)+(int)($current_lista)); $i++, $j++) {
    mezclar_generos(
        $generos, 
        $generos_A_P, 
        $cont_A_P, 
        $RANDOM, 
        $SEPARAR_GENERO, 
        $activar_permutacion, 
        $permutacion, 
        $permutado_pasado, 
        $conta, 
        $iniciar_R_2); // MEZCLA LOS GENEROS POR EL RANDOM 

    reproducir(
        $generos_A_P,
        $pasado, 
        $lista, 
        $comerciales, 
        $generos, 
        $escalar, 
        $comerciales_generos); // CREA LA LISTA DE REPRODUCCION

    $lista = array_filter($lista, function($item) {
        return in_array(get_extension_file($item), ['mp3','ogg']); 
    });

    $lista_reproducciones["ronda-$j"] = $lista;
}

$lista_reproduccion = array(
    "time" => time(),
    "lista" => $lista_reproducciones
);

$data_lista_reproduccion = new ListaReproduccion();
$data_lista_reproduccion->Set($lista_reproduccion);
$data_lista_reproduccion->Save();

echo "</br></br>Listas Generadas:</br></br>";
foreach($lista_reproducciones as $key => $value){
    echo "Lista: ".substr($key, 6)."</br>";
    echo "Canciones: </br>";
    foreach ($value as $_value) {
        echo "=> ".$_value."</br>";
    }
    echo "</br><hr>";
}

?>