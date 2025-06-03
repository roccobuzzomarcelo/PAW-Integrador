<?php

require __DIR__ . '/../vendor/autoload.php';

use PAW\src\Core\Router;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use PAW\src\Core\Config;
use PAW\src\Core\Request;

$config = new Config;

$log = new Logger('PawPrints-app');
$handler = new StreamHandler($config->get('LOG_PATH'));
$handler->setLevel($config->get('LOG_LEVEL'));
$log->pushHandler($handler);

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

$request = new Request;

$router = new Router;
$router->setLogger($log);
$router->get("/", "ControladorPagina@index");
$router->get("/cursos", "ControladorPagina@cursos");
$router->get("/curso", "ControladorPagina@curso");
$router->get("/ver-unidad", "ControladorPagina@verUnidad");
$router->get("/resolver-evaluacion", "ControladorPagina@resolverEvaluacion");
$router->post("/resolver-evaluacion", "ControladorPagina@procesarResolverEvaluacion");
$router->get("/agregar-curso", "ControladorPagina@agregarCurso");
$router->post("/agregar-curso", "ControladorPagina@procesarAgregarCurso");
$router->get("/agregar-unidades", "ControladorPagina@agregarUnidades");
$router->post("/agregar-unidades", "ControladorPagina@procesarAgregarUnidades");
$router->get("/agregar-evaluacion", "ControladorPagina@agregarEvaluacion");
$router->post("/agregar-evaluacion", "ControladorPagina@procesarAgregarEvaluacion");
$router->get("/resultado-evaluacion", "ControladorPagina@resultadoEvaluacion");
$router->get("/login", "ControladorPagina@login");
$router->post("/login", "ControladorPagina@procesarLogin");
$router->get("/register", "ControladorPagina@register");
$router->post("/register", "ControladorPagina@procesarRegistro");
$router->get("/user-profile", "ControladorPagina@userProfile");
$router->get('/faq', 'ControladorPagina@faq');