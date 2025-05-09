<?php

require __DIR__ . '/../vendor/autoload.php';

use PAW\src\App\Controlador\ControladorPagina;
use PAW\src\Core\Router;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$log = new Logger('PawPrints-app');
$log->pushHandler(new StreamHandler(__DIR__ . '/../log/app.log', Logger::DEBUG));

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

$router = new Router;
$router->get("/", "ControladorPagina@index");
$router->get("/cursos", "ControladorPagina@cursos");
$router->get("/curso", "ControladorPagina@curso");
$router->get("/agregar-curso","ControladorPagina@agregarCurso");
$router->post("/agregar-curso","ControladorPagina@procesarAgregarCurso");
$router->get("/agregar-unidades", "ControladorPagina@agregarUnidades");
$router->post("/agregar-unidades", "ControladorPagina@procesarAgregarUnidades");
$router->get("/agregar-evaluacion", "ControladorPagina@agregarEvaluacion");
$router->post("/agregar-evaluacion", "ControladorPagina@procesarAgregarEvaluacion");
$router->get("/login", "ControladorPagina@login");
$router->post("/login", "ControladorPagina@procesarLogin");
$router->get("/register","ControladorPagina@register");
$router->post("/register","ControladorPagina@procesarRegistro");
$router->get("/user-profile","ControladorPagina@userProfile");
$router->get('/faq', 'ControladorPagina@faq');
$router->get("not-found", "ControladorError@notFound");
$router->get("error-interno", "ControladorError@errorInterno");