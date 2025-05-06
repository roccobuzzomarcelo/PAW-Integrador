<?php

namespace PAW\src\Core;

use PAW\src\Core\Exceptions\RouteNotFoundException;

class Router{
    public array $rutas = [
        "GET" => [],
        "POST" => [],
    ];
    public function cargarRutas($path, $accion, $metodoHttp = "GET"){
        $this->rutas[$metodoHttp][$path] = $accion;
    }

    public function get($path, $accion){
        $this->cargarRutas($path, $accion, "GET");
    }

    public function post($path, $accion){
        $this->cargarRutas($path, $accion, "POST");
    }

    public function existeRuta($path, $metodoHttp){
        return array_key_exists($path, $this->rutas[$metodoHttp]);
    }

    public function getController($path, $metodoHttp){
        return explode("@", $this->rutas[$metodoHttp][$path]);
    }

    public function dirigir($path, $metodoHttp = "GET"){
        if(!$this->existeRuta($path, $metodoHttp)){
            throw new RouteNotFoundException("No existe la ruta {$path}");
        }
        list($controlador, $metodo) = $this->getController($path, $metodoHttp);
        $nombreClase = "PAW\\src\\App\\Controlador\\{$controlador}";
        $controladorObjeto = new $nombreClase();
        $controladorObjeto->$metodo();
    }
}