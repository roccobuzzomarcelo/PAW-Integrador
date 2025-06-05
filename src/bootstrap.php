<?php

require __DIR__ . '/../vendor/autoload.php';

use PAW\src\Core\Database\ConnectionBuilder;
use PAW\src\Core\Router;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use PAW\src\Core\Config;
use PAW\src\Core\Request;

$config = new Config;

$log = new Logger('Paw-Integrador');
$handler = new StreamHandler($config->get('LOG_PATH'));
$handler->setLevel($config->get('LOG_LEVEL'));
$log->pushHandler($handler);

$connectionBuilder = new ConnectionBuilder;
$connectionBuilder->setLogger($log);
$connection = $connectionBuilder->make($config);

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

$request = new Request;

$router = new Router;
$router->setLogger($log);
$router->get("/", "ControladorPagina@index");
$router->get('/faq', 'ControladorPagina@faq');
$router->get("/cursos", "ControladorCursos@cursos");
$router->get("/curso", "ControladorCursos@curso");
$router->get("/ver-unidad", "ControladorCursos@verUnidad");
$router->get("/resolver-evaluacion", "ControladorCursos@resolverEvaluacion");
$router->post("/resolver-evaluacion", "ControladorCursos@procesarResolverEvaluacion");
$router->get("/agregar-curso", "ControladorCursos@agregarCurso");
$router->post("/agregar-curso", "ControladorCursos@procesarAgregarCurso");
$router->get("/agregar-unidades", "ControladorCursos@agregarUnidades");
$router->post("/agregar-unidades", "ControladorCursos@procesarAgregarUnidades");
$router->get("/agregar-evaluacion", "ControladorCursos@agregarEvaluacion");
$router->post("/agregar-evaluacion", "ControladorCursos@procesarAgregarEvaluacion");
$router->get("/resultado-evaluacion", "ControladorCursos@resultadoEvaluacion");
$router->get("/login", "ControladorUsuarios@login");
$router->post("/login", "ControladorUsuarios@procesarLogin");
$router->get("/register", "ControladorUsuarios@register");
$router->post("/register", "ControladorUsuarios@procesarRegistro");
$router->get("/user-profile", "ControladorUsuarios@userProfile");