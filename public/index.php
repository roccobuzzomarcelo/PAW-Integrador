<?php

session_start();

require __DIR__ . '/../src/bootstrap.php';

$router->dirigir($request);