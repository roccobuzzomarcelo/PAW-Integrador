<?php

namespace PAW\src\App\Controlador;

use PAW\src\Core\Controlador;

class ControladorPagina extends Controlador
{
    public function index()
    {
        $titulo = "PAD - Inicio";
        require $this->viewsDir . 'index.view.php';
    }

    public function faq()
    {
        $titulo = "PAD - FAQ";
        require $this->viewsDir . 'faq.view.php';
    }
}