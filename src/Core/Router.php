<?php

namespace PAW\src\Core;

use PAW\src\Core\Exceptions\RouteNotFoundException;
use PAW\src\Core\Request;
use Exception;
use PAW\src\Core\Traits\Loggable;

class Router{
    use Loggable;
    public array $rutas = [
        "GET" => [],
        "POST" => [],
    ];

    public String $notFound = 'not-found';
    public String $internalError = 'error-interno';

    public array $dependencias = [];

    public function __construct(array $dependencias){
        $this->dependencias = $dependencias;
        $this->get($this->notFound, "ControladorError@notFound");
        $this->get($this->internalError, "ControladorError@errorInterno");
    }

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
        if(!$this->existeRuta($path, $metodoHttp)){
            throw new RouteNotFoundException("No existe la ruta {$path}");
        }
        return explode("@", $this->rutas[$metodoHttp][$path]);
    }

    public function call($controlador, $metodo){
        $nombreClase = "PAW\\src\\App\\Controlador\\{$controlador}";
        $controladorObjeto = new $nombreClase(...$this->dependencias);
        $controladorObjeto->$metodo();
    }

    public function dirigir(Request $request){
        try{
            list($path, $metodoHttp) = $request->route();
            list($controlador, $metodo) = $this->getController($path, $metodoHttp);
            $this->logger->info(
                "Código: 200 - Página encontrada",
                [
                    "Ruta" => $path, 
                    "Método" => $metodoHttp
                ]
            );
        } catch(RouteNotFoundException $e){
            list($controlador, $metodo) = $this->getController($this->notFound, "GET");
            $this->logger->debug(
                "Código: 404 - Página no encontrada", 
                [
                    "ERROR" => $e->getMessage(), 
                    "Método" => $metodoHttp
                ]
            );
        } catch(Exception $e){
            list($controlador, $metodo) = $this->getController($this->errorInterno, "GET");
            $this->logger->error(
                "Código: 500 - Error interno del Servidor", 
                [
                    "ERROR" => $e->getMessage(), 
                    "Ruta" => $path, 
                    "Método" => $metodoHttp
                ]
            );
        } finally{
            $this->call($controlador, $metodo);
        }
    }
}