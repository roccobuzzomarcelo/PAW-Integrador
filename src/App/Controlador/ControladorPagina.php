<?php

namespace PAW\src\App\Controlador;

class ControladorPagina
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

    public function index()
    {
        $titulo = "PAD - Inicio";
        require $this->viewsDir . 'index.view.php';
    }

    public function cursos()
    {
        $titulo = "PAD - Cursos";
        require $this->viewsDir . 'cursos.view.php';
    }

    public function login()
    {
        $titulo = "PAD - Login";
        require $this->viewsDir . 'login.view.php';
    }

    public function register()
    {
        $titulo = "PAD - Registro";
        require $this->viewsDir . 'register.view.php';
    }

    public function userProfile()
    {
        $titulo = "PAD - Perfil de Usuario";
        require $this->viewsDir . 'user-profile.view.php';
    }
}