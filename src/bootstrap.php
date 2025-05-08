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
$router->get("/login", "ControladorPagina@login");
$router->get("/register","ControladorPagina@register");
$router->get("/user-profile","ControladorPagina@userProfile");
$router->get("not-found", "ControladorError@notFound");
$router->get("error-interno", "ControladorError@errorInterno");