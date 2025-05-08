<?php

namespace PAW\src\App\Controlador;

class ControladorError
{
    public string $viewsDir;

    public function __construct()
    {
        $this->viewsDir = __DIR__ . "/../views/";
        $this->menu = [
            [
                "href" => "/",
                "name" => "Inicio"
            ],
            [
                "href" => "/cursos",
                "name" => "Cursos"
            ],
            [
                "href" => "/login",
                "name" => "Iniciar Sesion"
            ],
            [
                "href" => "/register",
                "name" => "Registrarse"
            ],
            [
                "href" => "/user-profile",
                "name" => "Perfil de Usuario"
            ],
        ];
    }

    public function notFound()
    {
        http_response_code(404);
        require $this->viewsDir . '404.view.php';
    }

    public function errorInterno()
    {
        http_response_code(500);
        require $this->viewsDir . '500.view.php';
    }
}