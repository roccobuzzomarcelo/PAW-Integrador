<?php

namespace PAW\src\App\Controlador;

use PAW\src\Core\Controlador;

class ControladorError extends Controlador
{
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