<?php

function index_of($array, $word) {
    foreach($array as $key => $value) if($value === $word) return $key;
    return -1;
}

function random() {
    return (float)(mt_rand() / (mt_getrandmax() + 1));
}

function get_random_int($min, $max) {
    return floor(random() * ($max - $min)) + $min;
}

// INSERTAR UN ITEM A LA LISTA
function insert_sattolo($array, $temporal, $escalar){
    remove_item_from_arr($array, $temporal);
    $lista = sattolo($array);
    array_splice($lista, $escalar, 0, $temporal);
    return $lista;
}

function sattolo($array) {
    $len = count($array);

    for ($i=0; $i<$len-1; $i++) {
        $j = floor(random() * ($len-($i+1))) + ($i+1);
        [$array[$i], $array[$j]] = [$array[$j], $array[$i]];
    }

    return $array;
}

function shuffle_array($array) {
    $len = count($array);

    for($i=$len-1; $i>0; $i--) {
        $j = floor(random() * ($i+1));
        [$array[$i], $array[$j]] = [$array[$j], $array[$i]];
    }

    return $array;
}

function burbuja_generos($array) {
    $len = count($array);

    for($i=1; $i<$len; $i++) {
        for($j=0; $j<($len-$i); $j++){
            if($array[$j]["ID"] > $array[$j+1]["ID"]) {
                [$array[$j], $array[$j+1]] = [$array[$j+1], $array[$j]];
            }
        }
    }

    return $array;
}

// REMOVER UN ITEM DE LA LISTA
function remove_item_from_arr($arr, $item) {
    $i = inde_of($arr, $item);
    if($i !== -1) {
        array_splice($arr, $i, 1);
    }

    return $arr;
}

// CONTROLA LA PERMUTACION
function permutaciones($array, $activar_permutacion, $generos_A_P, $permutacion, $permutado_pasado, $conta){
    $tam_array = count($array);
    $cadena = "";
    $fisher_elegido = "";

    if(!$activar_permutacion){
        for($i=0; $i<$tam_array; $i++){
            $cadena = $cadena.$generos_A_P[$i]["posicion_Perm"];
        }

        permuta("", $cadena, $permutacion);
        $activar_permutacion=true;
    }

    if(count($permutado_pasado) == $conta){
        $permutacion = $permutado_pasado;
        $permutado_pasado= array();
    }       
    
    if(count($permutado_pasado)==0 && count($permutacion)==$conta){
        $factorial = 1;

        for($i=1; $i<=count($generos_A_P); $i++) {
            $factorial = $factorial * $i;
        }

        $permutacion = shuffle_array($permutacion); // fisher yates a los elementos permutados

        $factorial = 1;

        for($i=1; $i<=count($generos_A_P); $i++) {
            $factorial = $factorial * $i;
        }

        $reacomodar = false;

        while($reacomodar==false){
            $reacomodar = true;
            $cont_perm = 0;

            for($j=0; $j<$factorial; $j++) {   
                for($i=1+$j; $i<$factorial; $i++) {
                    if(array_slice($permutacion[$j], -1) != array_slice($permutacion[$i], 0, 1)){
                        [$permutacion[$j+1], $permutacion[$i]] = [$permutacion[$i], $permutacion[$j+1]];
                        break;
                    }

                    if($i==$factorial-1 && $j==$i-1 && array_slice($permutacion[$j], -1) == array_slice($permutacion[$i], 0, 1)){
                        $permutacion = shuffle_array($permutacion);
                        $reacomodar=false;
                    }

                    $cont_perm++;

                    if($cont_perm > $factorial*$factorial){
                        $permutacion = shuffle_array($permutacion);
                        $reacomodar = false;
                        $j = $factorial;
                        break;
                    }
                }
            }
            
            // verificar si esta bien estructurado
            for($i=0; $i<$factorial-1; $i++){
                if(array_slice($permutacion[$i], -1) == array_slice($permutacion[$i+1], 0, 1)){
                    $permutacion = shuffle_array($permutacion);
                    $reacomodar = false;
                    break;
                }
            }
            
        }
//              alert('finnnnnnnnnn');
//              alert(permutacion);
        $tmp_permutacion = array();
        for($i=0, $j=$factorial-1; $j>=0; $j--, $i++){
            $tmp_permutacion[$i] = $permutacion[$j];
        }   
        
        $permutacion = $tmp_permutacion;
    }

    while(1){
        $fisher_elegido = array_pop($permutacion);
        if($fisher_elegido != null) break;
    }
//  console.log(permutacion);       
//  console.log('fisher:'+fisher_elegido);
    if(consultar_pasado($fisher_elegido, $permutado_pasado)){
        $generos_A_P = ordenar_permutacion($fisher_elegido, $generos_A_P);
        array_push($permutado_pasado, $fisher_elegido);
    }   
    
    
//          console.log("Combinaciones pendiente");
//          console.log(permutacion);
    //console.log(permutado_pasado.length+'='+conta);
    if(count($permutado_pasado)==$conta){
        //console.log("Combinaciones ya realizadas");
        //console.log(permutado_pasado);
    }

    return $generos_A_P;
}

// CARGA EN VARIABLE LAS DIFERENTES COMBINACIONES DE LA PERMUTACION
function permuta ($cad_I, $cad_D, $permutacion){
    if (count($cad_D) == 1){
        $permutacion[$conta++] = $cad_I + $cad_D;
        return $permutacion;
    }
    for ($i =0; $i<count($cad_D); $i++){
        permuta($cad_I + $cad_D{$i}, str_replace($cad_D{$i}, "", $cad_D));
    } 
    return $permutacion;
}

// ORDENA LA PERMUTACION
function ordenar_permutacion($fisher, $generos_A_P){
    for($i=0; $i<count($generos_A_P); $i++){
        for($j=0; $j<count($generos_A_P); $j++){
            if($generos_A_P[$j]["posicion_Perm"] == $fisher{$i}){
                [$generos_A_P[$i], $generos_A_P[$j]] = [$generos_A_P[$j], $generos_A_P[$i]];
            }
        }
    }

    return $generos_A_P;
}

// CONSULTA SI LA COMBINACION DE GENEROS EXISTENTE EN PERMUTACION YA FUE SELECCIONADO
function consultar_pasado($fisher_elegido, $permutado_pasado){
    $encontrar=true;

    for($i=0; $i<count($permutado_pasado); $i++){
        if($permutado_pasado[$i] == $fisher_elegido){
            $encontrar=false;
            break;
        }
    }

    return encontrar;
}


?>