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

    public function inscribir()
    {
        if (!isset($_SESSION['usuario'])) {
            echo "<script>alert('⚠️ Debes iniciar sesión para inscribirte'); window.location.href = '/login';</script>";
            return;
        }

        $usuarioId = $_SESSION['usuario']['id'];
        $cursoId = $_POST['curso_id'] ?? null;

        if (!$cursoId) {
            echo "<script>alert('⚠️ Curso no especificado'); window.history.back();</script>";
            return;
        }
        
        
        if ($this->modeloInstancia->existeInscripcion($usuarioId, $cursoId)) {
            echo "<script>alert('⚠️ Ya estás inscripto en este curso'); window.history.back();</script>";
            return;
        }

        $datos = [
            'usuario_id' => $usuarioId,
            'curso_id' => $cursoId,
            'fecha_inscripcion' => date('Y-m-d')
        ];

        $this->modeloInstancia->crearInscripcion($datos);

        echo "<script>alert('✅ Inscripción exitosa'); window.location.href = '/curso?id={$cursoId}';</script>";
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

        $inscriptos = $this->modeloInstancia->obtenerNombreInscriptosPorCurso($idCurso);
        $titulo = "Listado de inscriptos";
        require $this->viewsDir . 'inscriptos-lista.view.php';
    }
}

