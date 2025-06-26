<?php

namespace PAW\src\App\Controlador;

use PAW\src\Core\Controlador;
use PAW\src\App\Modelos\ColeccionInscripcion;

class ControladorInscripcion extends Controlador
{
    public ?string $modelo = ColeccionInscripcion::class;

    public function validarAdmin(): bool
    {
        if (!isset($_SESSION['usuario'])) {
            return false;
        }
        return $_SESSION['usuario']['tipo_usuario'] === 'admin';
    }

    // Muestra la cantidad total de inscriptos por curso
    public function cantidadInscriptos()
    {
        if (!$this->validarAdmin()) {
            echo "<script>alert('⚠️ Acceso no autorizado'); window.location.href = '/login';</script>";
            return;
        }

        $idCurso = $_GET['curso'] ?? null;
        if (!$idCurso) {
            echo "<script>alert('ID de curso no especificado'); window.history.back();</script>";
            return;
        }

        $total = $this->modeloInstancia->contarInscriptosPorCurso($idCurso);

        $titulo = "Inscriptos en el curso";
        require $this->viewsDir . 'inscriptos-total.view.php';
    }

    // Lista con los inscriptos al curso
    public function listarInscriptos()
    {
        if (!$this->validarAdmin()) {
            echo "<script>alert('⚠️ Acceso no autorizado'); window.location.href = '/login';</script>";
            return;
        }

        $idCurso = $_GET['id'] ?? null;
        if (!$idCurso) {
            echo "<script>alert('ID de curso no especificado'); window.history.back();</script>";
            return;
        }

        $inscriptos = $this->modeloInstancia->obtenerInscriptosPorCurso($idCurso);
        $titulo = "Listado de inscriptos";
        require $this->viewsDir . 'inscriptos-lista.view.php';
    }
}
