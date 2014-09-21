<?php
namespace Kumbia\Component;
class Request{


    protected $_vars = array(
        'url'       => NULL,
        'method' => NULL, //Método usado GET, POST, ...
        'module' => NULL, //Nombre del módulo actual
        'controller' => 'index', //Nombre del controlador actual
        'action' => 'index', //Nombre de la acción actual, por defecto index
        'parameters' => array(), //Lista los parámetros adicionales de la URL
    );

    function __set($name, $value){
        $this->_vars[$name] = $value;
        
    }

    function __get($name){
        return $this->_vars[$name];
    }



}