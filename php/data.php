<?php 
require_once 'data_file.php';

define('AUDIOS_RUTA', '../audios/');

class Data{
    private $_object;
    private $_path;

    function __construct($path, $object){
        $this->_path = $path;
        $this->_object = $object;
    }

    function Path(){ return $this->_path; }

    function Get(){ return $this->_object; }

    function GetString(){ return json_encode($this->_object, JSON_UNESCAPED_UNICODE); }

    function Set($values){
        $this->_object = (is_string($values)) ? json_decode($values, true) : $values;
    }

    function Save(){ return write_file($this->_path, $this->GetString()); }

    function Load(){
        $value = read_file($this->_path);

        if(array_key_exists('success', $value)){
            $this->Save();
            $value = read_file($this->_path);
        }

        $this->_object = $value;
    }
}

class GenerosAP extends Data{
    function __construct(){
        parent::__construct(
            "../json/generos_A_P.json",
            array(
                'current_lista' => '0',
                'generos_A_P'   => array(array(
                    'ID'                        => 0,
                    'Name'                      => "",
                    'AUSENTE_PRESENTE'          => array(),
                    'Ntracks'                   => "",
                    'carpeta'                   => "",
                    'lista'                     => array(),
                    'reproduccion'              => array(),
                    'contador'                  => '0',
                    'ultima'                    => '',
                    'posicion_Perm'             => '0',
                    'seleccion_pasado'          => array(),
                    'ID_comerciales_generos'    => '0',
                    'modo_revolver'             => '0'
                    )
                )
            ));
    }

    function Path(){ return parent::Path(); }
    function Get(){ return parent::Get(); }
    function GetString(){ return parent::GetString(); }
    function Set($values){ parent::Set($values); }
    function Save(){ return parent::Save(); }
    function Load(){ parent::Load(); return $this; }
}

class Generos extends Data{
    function __construct(){
        parent::__construct(
            "../json/generos.json",
            array(
                array(
                    'ID'                        => 0,
                    'Name'                      => "",
                    'AUSENTE_PRESENTE'          => array(),
                    'Ntracks'                   => "",
                    'carpeta'                   => "",
                    'lista'                     => array(),
                    'reproduccion'              => array(),
                    'contador'                  => '0',
                    'ultima'                    => '',
                    'posicion_Perm'             => '0',
                    'seleccion_pasado'          => array(),
                    'ID_comerciales_generos'    => '0',
                    'modo_revolver'             => '0'
                )
            ));
    }

    function Path(){ return parent::Path(); }
    function Get(){ return parent::Get(); }
    function GetString(){ return parent::GetString(); }
    function Set($values){ parent::Set($values); }
    function Save(){ return parent::Save(); }
    function Load(){ parent::Load(); return $this; }
}

class Comerciales extends Data{
    function __construct(){
        parent::__construct(
            "../json/comerciales.json",
            array(
                array(
                    'ID'                        => 0,
                    'tipo'                      => "",
                    'descripcion'               => array(),
                    'Ntracks'                   => "",
                    'carpeta'                   => "",
                    'lista'                     => array(),
                    'reproduccion'              => array(),
                    'contador'                  => '0',
                    'ultima'                    => '',
                    'seleccion_pasado'          => array(),
                    'modo_revolver'             => '0',
                    'p_eliminar'                => ''
                )
            ));
    }

    function Path(){ return parent::Path(); }
    function Get(){ return parent::Get(); }
    function GetString(){ return parent::GetString(); }
    function Set($values){ parent::Set($values); }
    function Save(){ return parent::Save(); }
    function Load(){ parent::Load(); return $this; }
}

class Lista extends Data{
    function __construct(){
        parent::__construct(
            "../json/lista.json",
            array(
                'time_control'      => date('Y-m-d G:i:s'),
                'current_lista'     => 0,
                'lista'             => json_encode(array(),JSON_UNESCAPED_UNICODE),
                'revolver'          => 'false'
            ));
    }

    function Path(){ return parent::Path(); }
    function Get(){ return parent::Get(); }
    function GetString(){ return parent::GetString(); }
    function Set($values){ parent::Set($values); }
    function Save(){ return parent::Save(); }
    function Load(){ parent::Load(); return $this; }
}

class ListaReproduccion extends Data{
    function __construct(){
        parent::__construct(
            "../json/lista_reproduccion.json",
            array(
                'time_control' => date('Y-m-d G:i:s'),
                'current_lista' => 0,
                'lista'         => json_encode(array(),JSON_PRETTY_PRINT),
                'revolver'      => 'false'
            ));
    }

    function Path(){ return parent::Path(); }
    function Get(){ return parent::Get(); }
    function GetString(){ return parent::GetString(); }
    function Set($values){ parent::Set($values); }
    function Save(){ return parent::Save(); }
    function Load(){ parent::Load(); return $this; }
}

class General extends Data{
    function __construct(){
        parent::__construct(
            "../json/general.json",
            array(
                'RANDOM'                    => "0",
                'PIZZICATO'                 => "0",
                'SEPARAR_GENERO'            => "1",
                'nronda'                    => 0,
                'temporal'                  => "",
                'escalar'                   => -1,
                'cont_A_P'                  => 0,
                'temporal_A_P'              => 0,
                'permutacion'               => array(),
                'permutado_pasado'          => array(),
                'activar_permutacion'       => false,
                'comerciales_generos'       => true,
                'conta":0,"iniciar_R_2'     => true,
                'version'                   => "1",
                'tiempo_inactividad'        => "1",
                'usuario'                   => "IVAN",
                'clave'                     => "2727",
                'nombre_emisora'            => "",
                'color_emisora'             => "000000",
                'letra_emisora'             => "35",
                'slogan_emisora'            => "",
                'color_slogan'              => "0000FF",
                'letra_slogan'              => "30",
                'url_logo'                  => "",
                'ancho_logo'                => "70",
                'largo_logo'                => "70",
                'redondeo'                  => "10"
            ));
    }

    function Path(){ return parent::Path(); }
    function Get(){ return parent::Get(); }
    function GetString(){ return parent::GetString(); }
    function Set($values){ parent::Set($values); }
    function Save(){ return parent::Save(); }
    function Load(){ parent::Load(); return $this; }
}

 ?>