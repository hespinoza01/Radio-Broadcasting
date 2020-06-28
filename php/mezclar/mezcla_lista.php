<?php

require_once 'revolver.php';

function mezclar_generos(&$generos, &$generos_A_P, &$cont_A_P, &$RANDOM, &$SEPARAR_GENERO, &$activar_permutacion, &$permutacion, &$permutado_pasado, &$conta, &$iniciar_R_2) {
    $temp_genero = array();
    $primero_ultimo = 0;
    $primero = null;
    $ultimo = null;

    if($cont_A_P == -1){   
        $temp_genero = mostrar_ausente_presente($generos, $cont_A_P, $RANDOM);

        for($i=0; $i<count($generos); $i++){
            $generos[$i]["posicion_Perm"]=$i; // coloca las posiciones iniciales de la lista generos
        }                   
    }
    else
        $temp_genero=$generos_A_P;

    if(count($temp_genero) >= 3){
        $ultimo = $temp_genero[count($temp_genero)-1]["Name"];

        $cont_A_P = incrementar_A_P($generos, $cont_A_P);

        $encontrar = false;
        $detener = 0;

        while($encontrar == false){
            $encontrar = true;
            $generos_A_P = mostrar_Ausente_Presente($generos, $cont_A_P, $RANDOM);

            if($RANDOM == 0){ // SIN RANDOM
                //alert('pasooooooooooooooooooo');
                $generos_A_P = burbuja_generos($generos_A_P);
            }
            if($RANDOM == 1){ // MEZCLA GENEROS CON FISHER YATES
                $generos_A_P = shuffle_array($generos_A_P);
            }
            else if($RANDOM == 2){ // MEZCLA GENEROS CON SATTOLO
                //console.log(RANDOM);
                $generos_A_P = sattolo($generos_A_P);
            }
            else if($RANDOM == 3){ // MEZCLA GENEROS CON PERMUTACION
                //console.log(permutacion.length+"="+permutado_pasado.length);
                $generos_A_P = permutaciones($generos_A_P, $activar_permutacion, $generos_A_P, $permutacion, $permutado_pasado, $conta); 
                
            }
            // INSTRUCCIONES PARA SEPARAR GENEROS IGUALES ___________________________________________________________________________________________________
            if($SEPARAR_GENERO == 2){ 
                if($RANDOM == 1 || $RANDOM == 2){
                    if(count($generos_A_P)>=4){
                        $cont_rep = 0;
                        
                        for($i=0, $j=1; $i<count($generos_A_P)-1; $i++, $j++){
                            if($generos_A_P[$i]["Name"] == $generos_A_P[$j]["Name"]){
                                $cont_rep++;
                            }
                        }

                        if($cont_rep > 0){
                            $encontrar=false;    
                        }
                        $cont_rep = 0;
                    }
                }
                //else if($RANDOM == 3) alert("El modo permutacion no separa los generos");
            }
            // FIN INSTRUCCIONES PARA SEPARAR GENEROS IGUALES _______________________________________________________________________________________________
//                  mostrarListaGeneros(generos_A_P);
            $primero = $generos_A_P[0]["Name"];
    
            if($iniciar_R_2 != false){
                if($ultimo == $primero ){
                    $encontrar = false;
                    $primero_ultimo++;
 
                    if($RANDOM!=3){
                        $generos_A_P = $temp_genero;
                    }
                    if($RANDOM==3){
                    //  alert("Ultimo genero es igual al permutacion"); 
                        array_push($permutacion, array_pop($permutado_pasado));
                    //  console.log(permutacion.length+"="+permutado_pasado.length);
                    }
                    
                }
            }else $iniciar_R_2=true;
        }

    }
    else{ 
        if($RANDOM == 1) echo("<script> console.log('Debe poseer 3 o más generos para mezclar con fisher yates')</script>");
        else if($RANDOM ==2) echo("<script> console.log('Debe poseer 3 o más generos para mezclar con sattolo')</script>");
        else if($RANDOM ==3) echo("<script> console.log('Debe poseer 3 o más generos para mezclar con permutacion')</script>");
    }
}


// RECORRIDO DE AUSENTE PRESENTE EN LOS GENEROS
function incrementar_A_P($generos, $cont_A_P){
    $cont_A_P++;

    if($cont_A_P == count($generos[0]["AUSENTE_PRESENTE"]))
        $cont_A_P=0;

    return $cont_A_P;
}


// APLICA AUSENTE PRESENTE EN LOS GENEROS
function mostrar_ausente_presente($generos, $cont_A_P, $RANDOM) {
    $generos_p = array();
    $temporal_A_P = $cont_A_P;

    if($cont_A_P == -1){
        $cont_A_P=0;

        for($i=0, $j=0; $i<count($generos); $i++){
            if((int)($RANDOM) == 3 && (int)($generos[$i]["Ntracks"])!=0){
                $generos_p[$j++]=$generos[$i];
            }
            else if((int)($generos[$i]["Ntracks"])!=0 && (int)($generos[$i]["AUSENTE_PRESENTE"][(int)($cont_A_P)])!=0 && count($generos[$i]["AUSENTE_PRESENTE"])!=0){
                $generos_p[$j++]=$generos[$i];
            }
        }
        $cont_A_P= -1;
    } else {
        for($i=0, $j=0; $i<count($generos); $i++){
            $generos[$i]["posicion_Perm"] = $i;
            if((int)($RANDOM) == 3 && (int)($generos[$i]["Ntracks"])!=0){
                $generos_p[$j++]=$generos[$i];
            }
            else if((int)($generos[$i]["Ntracks"])!=0 && (int)($generos[$i]["AUSENTE_PRESENTE"][(int)($cont_A_P)])!=0 && count($generos[$i]["AUSENTE_PRESENTE"])!=0){
                $generos_p[$j++]=$generos[$i];
            }
        }
    }

    return  $generos_p;
}

// ===============================================

// CREA LA LISTA DE REPRODUCCION
function reproducir(&$generos_A_P, &$pasado, &$lista, &$comerciales, &$generos, &$escalar, &$comerciales_generos){
    $i=0; $j=0; $k=0;
    $l_reproducir = array();
    $pos_A_P=0;

    lista_reproduccion_generos($generos_A_P, $generos, $pasado, $lista, $escalar);   // asigna a reproduccion los tracks de generos a reproducir
    for($i=0; $i<count($generos_A_P); $i++){
        
        $comerciales = limpiar_reproduccion_comerciales($comerciales); // limpia la variable reproduccion de los comerciales
        
        lista_reproduccion_comerciales($comerciales, $pasado, $lista, $escalar); // asigna a reproduccion los tracks de comerciales a reproducir
        // cargando los comerciales de los generos******************************************************
        if($comerciales_generos == false){
            $comerciales_generos = true;
        }
        else{
            for($w=0; $w<count($comerciales); $w++){
                if($comerciales[$w]["ID"] == $generos_A_P[$i]["ID_comerciales_generos"]){
                    $inicio = (int)($comerciales[$w]["contador"]);
                    $comerciales[$w]["reproduccion"] = array();

                    for($p=0; $p<(int)($comerciales[$w]["Ntracks"]); $p++){
                        $comerciales[$w]["reproduccion"][$p] = $comerciales[$w]["lista"][$inicio++];
                    }

                    $comerciales[$w]["contador"] = (int)($inicio);

                    if(count($comerciales[$w]["lista"]) == $inicio){
                        mezclar($comerciales[$w]["lista"], $w, $comerciales[$w], $pasado, $lista, $escalar);
                        $comerciales[$w]["contador"] = 0; // ultima posicion de la lista comerciales de reproduccion
                        $inicio = 0;   
                    }

                    for($z=0; $z<count($comerciales[$w]["reproduccion"]); $z++){
                        $l_reproducir[$k++] = $comerciales[$w]["reproduccion"][$z];
                    }
                }
            }
        }
        // fin comerciales de los generos*************************************************************************
        // cargando generos****************************************************************************************
        for($j=0; $j<count($generos_A_P[$i]["reproduccion"]); $j++){
            $l_reproducir[$k++] = $generos_A_P[$i]["reproduccion"][$j];
        }
        // fin de genero ******************************************************************************************
        
        for($w=0; $w<count($comerciales); $w++){
            // cargando comerciales entradas *************************************************************************
            if((int)($comerciales[$w]["tipo"]) == 3){
                for($z=0; $z<count($comerciales[$w]["reproduccion"]); $z++){
                    $l_reproducir[$k++] = $comerciales[$w]["reproduccion"][$z];
                }
            }
            // fin comerciales entradas ********************************************************************************
        }
                
        for($w=0; $w<count($comerciales); $w++){
            // cargando comerciales generales *************************************************************************
            if((int)($comerciales[$w]["tipo"]) == 1){
                for($z=0; $z<count($comerciales[$w]["reproduccion"]); $z++){
                    $l_reproducir[$k++] = $comerciales[$w]["reproduccion"][$z];
                }
            }
            // fin comerciales *****************************************************************************************
        }
        
    }
    $generos = limpiar_reproduccion_generos($generos);
    $lista = $l_reproducir; 
}


// AGREGA A GENEROS LOS TRACKS A LA LISTA DE REPRODUCCION
function lista_reproduccion_generos(&$generos_A_P, &$generos, &$pasado, &$lista, &$escalar){
    $i=0; $j=0; $k=0;
    $inicio=0;

    // Añandiendo a lista de reproduccion los generos
    for($i=0; $i<count($generos_A_P); $i++){
        $inicio = (int)($generos_A_P[$i]["contador"]);

        $j = $inicio;
        if(($inicio+(int)($generos_A_P[$i]["Ntracks"])) <= count($generos_A_P[$i]["lista"])){
            for($j=$inicio, $k=0; $j<(int)($generos_A_P[$i]["Ntracks"])+$inicio; $j++,$k++){
                $generos_A_P[$i]["reproduccion"][$k] = $generos_A_P[$i]["lista"][$j];
            }
        // aqui va ultima posicion y audio  
        }else if($j<count($generos_A_P[$i]["lista"])){
            for($j=$inicio,$k=0; $j<count($generos_A_P[$i]["lista"]); $j++,$k++){
                $generos_A_P[$i]["reproduccion"][$k] = $generos_A_P[$i]["lista"][$j];
            }

            if($k < (int)($generos_A_P[$i]["Ntracks"])){
                mezclar($generos_A_P[$i]["lista"], $i, $generos_A_P[$i], $pasado, $lista, $escalar);
            //  console.log('Prueba4:');
                $tope = (int)($generos_A_P[$i]["Ntracks"]) - $k;                                        // prueba 4
                $w=0;
                for($w=0; $w<$tope; $w++,$k++){
                //  console.log(w);
                    //console.log(parseInt(generos_A_P[i].Ntracks)-k);
                    $generos_A_P[$i]["reproduccion"][$k]=$generos_A_P[$i]["lista"][$w];
                }
            //  console.log('Reproduccion2:');                                                                      // prueba 3
            //  console.log(generos_A_P[i].reproduccion);
                $j=$w;
            }
        }
                                                                    
        $generos_A_P[$i]["contador"] = (int)($j); // ultima posicion de la lista genero de reproduccion
        $generos_A_P[$i]["ultima"] = $generos[$i]["lista"][$j]; // ultimo audio de la lista de reproduccion
        if($generos_A_P[$i]["lista"]==(int)($j)){
            mezclar($generos_A_P[$i]["lista"], $i, $generos_A_P[$i], $pasado, $lista, $escalar);
            $generos_A_P[$i]["contador"]=0; // ultima posicion de la lista genero de reproduccion
            $generos_A_P[$i]["ultima"] = "";
            $j=0;
        }
    }
}


// AGREGAR A COMERCIALES LOS TRACKS A LA LISTA DE REPRODUCCION
function lista_reproduccion_comerciales(&$comerciales, &$pasado, &$lista, &$escalar){
    // Añandiendo a lista de reproduccion los comerciales
    $i=0; $j=0; $k=0; 
    $inicio=0;

    for($i=0; $i<count($comerciales); $i++){
        $inicio = (int)($comerciales[$i]["contador"]);

        if((int)($comerciales[$i]["tipo"]) != 2){
            if(($inicio+(int)($comerciales[$i]["Ntracks"])) <= count($comerciales[$i]["lista"])){
                for($j=$inicio,$k=0; $j<(int)($comerciales[$i]["Ntracks"])+$inicio; $j++,$k++){
                    $comerciales[$i]["reproduccion"][$k]=$comerciales[$i]["lista"][$j];
                }
            // aqui va ultima posicion y audio  
            }else if($j < count($comerciales[$i]["lista"])){
                for($j=$inicio,$k=0; $j<count($comerciales[$i]["lista"]); $j++,$k++){
                    $comerciales[$i]["reproduccion"][$k]=$comerciales[$i]["lista"][$j];
                }
                if($k < (int)($comerciales[$i]["Ntracks"])){
                    mezclar($comerciales[$i]["lista"], $i, $comerciales[$i], $pasado, $lista, $escalar);
                    $tope = (int)($comerciales[$i]["Ntracks"]) - $k;    
                    
                    for($w=0; $w<$tope; $w++,$k++){
                        $comerciales[$i]["reproduccion"][$k] = $comerciales[$i]["lista"][$w];
                    }
                    $j=$w;
                }
            }

            $comerciales[$i]["contador"] = $j; // ultima posicion de la lista genero de reproduccion
            $comerciales[$i]["ultima"] = (isset($comerciales[$i]["lista"][$j])) ? $comerciales[$i]["lista"][$j] : null; // ultimo audio de la lista de reproduccion
            if(count($comerciales[$i]["lista"]) == $j){
                mezclar($comerciales[$i]["lista"], $i, $comerciales[$i], $pasado, $lista, $escalar);
                $comerciales[$i]["contador"] = 0; // ultima posicion de la lista genero de reproduccion
                $comerciales[$i]["ultima"] = "";
                $j=0;
            }
        }
    }
}


// LIMPIA LA LISTA DE REPRODUCCION DE LOS GENEROS
function limpiar_reproduccion_generos($generos){
    for($i=0; $i<count($generos); $i++){
        $generos[$i]["reproduccion"] = array();   
    }

    return $generos;
}

// LIMPIA LA LISTA DE REPRODUCCION DE LOS COMERCIALES
function limpiar_reproduccion_comerciales($comerciales){
    for($i=0; $i<count($comerciales); $i++){
        $comerciales[$i]["reproduccion"] = array();   
    }
    return $comerciales;
}
// ==========================================================================


// MEZCLAR LA LISTA DE ACUERDO AL MODO DE REVOLVER
function mezclar(&$array, $pos, &$generos_A_P_T, &$pasado, &$lista, &$escalar){ 
    $tempLista = array();
    $fisherLista= array();
    $ultimoLista;
    $len = count($array);
    // copiando datos al nuevo arreglo
    //alert(generos_A_P_T.modo_revolver
    
    for($i=0; $i<$len; $i++){
        $tempLista[$i] = $array[$i];
        $fisherLista[$i] = $array[$i];
    }
    
    $constante = shuffle_array($fisherLista);
    
            
    if($generos_A_P_T["modo_revolver"] == 4){
        $algoritmo = seleccionar_algoritmo_revolver($generos_A_P_T["p_eliminar"]);
        if($algoritmo==0) echo('<script> console.log("Algoritmo Escogido: Fisher Yates")</script>');
        if($algoritmo==1) echo('<script> console.log("Algoritmo Escogido: Sattolo")</script>');
    }
    
    
    if($generos_A_P_T["modo_revolver"] == 1 || $generos_A_P_T["modo_revolver"] == 2 || $generos_A_P_T["modo_revolver"] == 3 ){
        for($j=0; $j<$len; $j++){
            $temporal=$constante[$j];

            if(count($generos_A_P_T["seleccion_pasado"])==0 && $temporal==$pasado && $j==0){
                echo('<script> console.log("TEMPORAL ES IGUAL A PASADO")</script>');
                $temporal=$constante[$j+1];
            }
            //console.log('tamano genero pasado:'+generos_A_P_T.seleccion_pasado.length);
            if(index_of($generos_A_P_T["seleccion_pasado"], $temporal)==-1){
                array_push($generos_A_P_T["seleccion_pasado"], $temporal);//constante[j]);

                if(index_of($tempLista, $temporal)==$len-1 && $generos_A_P_T["modo_revolver"] == 1){
                    array_pop($generos_A_P_T["seleccion_pasado"]);   
                    continue;
                }
                break;  
            }
        }
        //console.log('temporal2:'+temporal);
        //console.log('pasado2:'+pasado);
        if(count($generos_A_P_T["lista"]) == count($generos_A_P_T["seleccion_pasado"])){
            $pasado = array_pop($generos_A_P_T["seleccion_pasado"]);
            //console.log('temporal:'+temporal);
            //console.log('pasado:'+pasado);
            $generos_A_P_T["seleccion_pasado"] = array();
            //generos_A_P_T.seleccion_pasado.push(pasado);
        }
    
        $escalar = index_of($tempLista, $temporal);
        // verificación
        echo("<script> console.log('Audio Escogido=".$temporal." posicion=".$escalar."')</script>");
    }
    echo("<script> console.log('Array Antes=[");
    echo(implode(",", $tempLista));
    echo("]')</script>");
    $ultimoLista = $tempLista[$len-1];
    
    if($generos_A_P_T["modo_revolver"] == 2){   
        $lista = insert_sattolo($array, $temporal, $escalar);
    }

    if($generos_A_P_T["modo_revolver"] == 1){

        $lista = insert_sattolo($array, $temporal, 0);
        $validacion=false;
        while($validacion==false){
            
            $validacion=true;
            if(count($array)-1==count($generos_A_P_T["seleccion_pasado"])){
                if(index_of($generos_A_P_T["seleccion_pasado"], $lista[count($array)-1])==-1){
                    //console.log('solucion_________________________________________________');
                    
                    //console.log(array);
                    //console.log(tempLista);
                    //console.log('___________________________________________________________');
                    //array=tempLista;
                    $lista = insert_sattolo($array, $temporal, 0);
                    if(index_of($generos_A_P_T["seleccion_pasado"], $lista[count($array)-1])!=-1){
                        //.......
                    }
                    else
                        $validacion=false;                       
                }
            }
            
        }
       
    }

    if($generos_A_P_T["modo_revolver"] == 3){
        $lista = insert_sattolo($array, $temporal, count($array)-1);
    }
    
    if($generos_A_P_T["modo_revolver"] == 4){ 
        if($algoritmo==0){
            $lista = shuffle_array($array);
        }else if($algoritmo==1){
            $lista = sattolo($array);
        }
    }

    $encontrar=false;
    $contador;
    $contador2;
    $j=0;
    while($encontrar == false){
        $encontrar = true;
        $contador=0;
        $contador2=0;

        for($i=0; $i<count($lista); $i++){
            if(!isset($tempLista[$i]) || !isset($lista[$i])) continue;
            if($tempLista[$i] != $lista[$i])
                break;
            $contador++;
        }
        //Paso 1.- verificacion de igualdad
        if($contador==count($lista)){
            $encontrar = false;
            echo("<script> console.log('son iguales')</script>");

            if($generos_A_P_T["modo_revolver"] == 2){   
                $lista = insert_sattolo($array, $temporal, $escalar);
            }
            if($generos_A_P_T["modo_revolver"] == 1){
                $lista = insert_sattolo($array, $temporal, 0);
            }
            if($generos_A_P_T["modo_revolver"] == 3){ 
                $lista = insert_sattolo($array, $temporal, count($array)-1);
            }
            if($generos_A_P_T["modo_revolver"] == 4){ 
                if($algoritmo==0){
                    $lista = shuffle_array($array);
                }else if($algoritmo==1){
                    $lista = sattolo($array);
                }
            }
            $contador=0;
        }
        //Paso 2.- verificacion de ultimo con primero
        if($ultimoLista == $array[0]){
            $encontrar = false;
            if($generos_A_P_T["modo_revolver"] == 2){   
                $lista = insert_sattolo($array, $temporal, $escalar);
            }
            if($generos_A_P_T["modo_revolver"] == 1){
                echo("<script> console.log('ultimo igual a primero 2')</script>");
                //console.log(array);
                $lista = insert_sattolo($array, $temporal, 0);
                //console.log(lista);
            }
            if($generos_A_P_T["modo_revolver"] == 3){
                echo("<script> console.log('ultimo igual a primero 3')</script>");
                $lista = insert_sattolo($array, $temporal, count($array)-1);
            }
            if($generos_A_P_T["modo_revolver"] == 4){ 
                echo("<script> console.log('ultimo igual a primero 4')</script>");
                if($algoritmo==0){
                    $lista = shuffle_array($array);
                }else if($algoritmo==1){
                    $lista = sattolo($array);
                }
            }
        }
        //Paso 3.- verificacion de posicion repetida
        
        if($generos_A_P_T["modo_revolver"] == 3){
            $t_lista = $tempLista;
            $t_lista = remove_item_from_arr($t_lista, $lista[count($lista)-1]);
            //console.log(t_lista);
            for($i=0,$j=0; $i<count($lista)-1; $i++){
                if(!isset($t_lista[$i]) || !isset($lista[$i])) continue;
                if($t_lista[$i] == $lista[$i])
                    $contador2++;
            }
            if($contador2>0){
                $encontrar = false;
                echo("<script> console.log('hay una posicion repetida3')</script>");
                $lista = insert_sattolo($array, $temporal, count($array)-1);   
            }
            $contador2=0;
        }
        if($generos_A_P_T["modo_revolver"] == 2){
            for($i=0; $i<count($lista); $i++){
                if(!isset($tempLista[$i]) || !isset($lista[$i])) continue;
                if($tempLista[$i] == $lista[$i]){
                    $contador2++;
                }
            }
            if($contador2>1){
                $encontrar = false;
                echo("<script> console.log('hay una posicion repetida1')</script>");
                $lista = insert_sattolo($array, $temporal, $escalar);
            }
            $contador2=0;
        }
        if($generos_A_P_T["modo_revolver"] == 1){   // ...................................
            $t_lista = $tempLista;
            $t_lista = remove_item_from_arr($t_lista, $lista[0]);
            //console.log(t_lista);
            //console.log(lista);
            for($i=1,$j=0; $i<count($lista); $i++,$j++){
                //console.log(t_lista[j]+'='+lista [i]);
                if(!isset($t_lista[$j]) || !isset($lista[$j])) continue;
                if($t_lista[$j] == $lista[$i]){
                    $contador2++;
                }
            }
            //console.log('contador2:'+contador2);
            if($contador2>0){
                $encontrar = false;
                echo("<script> console.log('hay una posicion repetida2')</script>");
                //console.log(array);
                $lista = insert_sattolo($array, $temporal, 0);
                
                for($i=0; $i<count($array); $i++){
                    if(index_of($generos_A_P_T["seleccion_pasado"], $array[$i])==-1){
                        $faltante = $array[$i];
                        //console.log("Faltante:"+array[i]);
                        if($faltante==$lista[count($array)-1]){
                            //console.log('pruebaaaaaaaaaaaaaaaaaaaaaaaaaaaaa');
                            $lista = insert_sattolo($array, $temporal, 0);
                        }
                    }
                }
                
                //console.log(lista);
            }
            $contador2=0;
        }
        if($generos_A_P_T["modo_revolver"] == 4 && $algoritmo==1){   // ...................................
            $t_lista = $tempLista;
            for($i=0; $i<count($lista); $i++,$j++){
                if($t_lista[$i] == $lista[$i]){
                    $contador2++;
                }
            }

            if($contador2>0){
                $encontrar = false;
                echo("<script> console.log('hay una posicion repetida4')</script>");
                if($algoritmo==0){
                    $lista = shuffle_array($array);
                }else if($algoritmo==1){
                    $lista = sattolo($array);
                }
            }
            $contador2=0;
        }
    }
    // verificación
    echo("<script> console.log('Array Nuevo=[");
    echo(implode(", ", $lista));
    echo("]')</script>");
//          document.getElementById("txtArray").innerHTML = "["+lista+"]";
    $escalar=-1;
}

function seleccionar_algoritmo_revolver($p_eliminar){
    $arreglo=[1,2,3,4,5,6,7,8];
    $porcentaje=(float)($p_eliminar);
    $n_eliminar=(int)(count($arreglo)*$porcentaje/100);
    $eliminado_sattolo=array();
    $eliminado_fisher=array();
    $elegido=0;
    $total_sattolo=0;
    $total_fisher=0;
    
    
    // ELECCION PARA FISHER YATES
    while(1){
        $elegido=get_random_int(0,8);
        if(index_of($eliminado_fisher, $elegido)==-1){
            array_push($eliminado_fisher, $elegido);                         
        }
        if(count($eliminado_fisher)==$n_eliminar) break;
    }
    //alert(eliminado_fisher);
    
    // ELECCION PARA SATTOLO
    while(1){
        $elegido=get_random_int(0,8);
        if(index_of($eliminado_sattolo, $elegido)==-1){
            array_push($eliminado_sattolo, $elegido);                            
        }
        if(count($eliminado_sattolo)==$n_eliminar) break;
    }
    //alert(eliminado_sattolo);
    //alert(arreglo);
    
    // ELIMINAR LOS ESCOGIDOS DE FISHER DEL ARREGLO
    $tmp_fisher=array();
    for($i=0; $i<count($arreglo); $i++){
        $tmp_fisher[$i]=$arreglo[$i];
    }
    for($i=0; $i<$n_eliminar; $i++){
        $tmp_fisher = remove_item_from_arr($tmp_fisher, $arreglo[$eliminado_fisher[$i]]);
    }

    echo("<script> console.log('Restantes Fisher: ");
    echo(implode(", ", $tmp_fisher));
    echo "')</script>";
    
    // ELIMINAR LOS ESCOGIDOS DE SATTOLO DEL ARREGLO
    $tmp_sattolo= array();
    for($i=0; $i<count($arreglo); $i++){
        $tmp_sattolo[$i] = $arreglo[$i];
    }
    
    for($i=0; $i<$n_eliminar; $i++){
        $tmp_sattolo = remove_item_from_arr($tmp_sattolo, $arreglo[$eliminado_sattolo[$i]]);
    }
    echo("<script> console.log('Restantes Sattolo: ");
    echo(implode(", ", $tmp_sattolo));
    echo "')</script>";
    
    
    for($j=0; $j<count($tmp_fisher); $j++){   
        $total_fisher = $total_fisher+(int)($tmp_fisher[$j]);
        $total_sattolo = $total_sattolo+(int)($tmp_sattolo[$j]);
    }
    if($total_fisher == $total_sattolo) {
        echo("<script> console.log('La suma de fisher y sattollo son iguales')</script>");
        seleccionar_algoritmo_revolver($p_eliminar);
    }
    else {
        echo("<script> console.log('Total Fisher:".$total_fisher.",Total Sattolo:".$total_sattolo."')</script>");
    }
    if($total_fisher>$total_sattolo){
        return 0;
    }
    else{
        return 1;
    }
}


?>