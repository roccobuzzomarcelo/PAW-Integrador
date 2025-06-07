<?php

namespace PAW\src\App\Controlador;

use PAW\src\Core\Controlador;
use PAW\src\App\Modelos\ColeccionEvaluacion;

class ControladorEvaluacion extends Controlador
{
    public ?string $modelo = ColeccionEvaluacion::class;

    public function validarAdmin(): bool
    {
        if (!isset($_SESSION['usuario'])) {
            return false;
        }
        $tipo_usuario = $_SESSION['usuario']['tipo_usuario'] ?? null;
        return $tipo_usuario === 'admin';
    }
    public function validarSesion()
    {
        if (!isset($_SESSION['usuario'])) {
            echo "<script>alert('⚠️ Debes iniciar sesion para ver un curso'); window.location.href = '/login'</script>";
            return;
        }
    }

    public function agregarEvaluacion()
    {
        if (!$this->validarAdmin()) {
            return;
        }

        $idCurso = $_GET['id'] ?? null;
        $titulo = "PAD - Agregar Evaluacion";

        require $this->viewsDir . 'agregar-evaluacion.view.php';
    }


    public function procesarAgregarEvaluacion()
    {
        if (!$this->validarAdmin()) {
            echo "<script>alert('⚠️ Acceso no autorizado'); window.history.back();</script>";
            return;
        }

        global $request;

        $tituloEvaluacion = $request->get("titulo");
        $idCurso = $request->get('id_curso');

        if (!$idCurso) {
            echo "<script>alert('⚠️ ID del curso no especificado');</script>";
            return;
        }

        $preguntas = $request->get("preguntas"); // Esto depende de cómo estés enviando las preguntas

        // 1. Insertar evaluación
        $datosEvaluacion = [
            'titulo' => $tituloEvaluacion,
            'id_curso' => $idCurso,
        ];
        $evaluacionId = $this->modeloInstancia->crearEvaluacion($datosEvaluacion);

        if (!$evaluacionId) {
            echo "<script>alert('⚠️ Error al crear la evaluación'); window.history.back();</script>";
            return;
        }

        // 2. Insertar preguntas y sus opciones
        foreach ($preguntas as $pregunta) {
            $datosPregunta = [
                'id_evaluacion' => $evaluacionId,
                'enunciado' => $pregunta['enunciado'],
                'tipo' => $pregunta['tipo'],
                // Guardar respuesta_correcta solo para preguntas tipo completar
                'palabra_correcta' => $pregunta['tipo'] === 'completar'
                    ? ($pregunta['opciones'][0]['respuesta_correcta'] ?? '')
                    : null,
            ];

            $preguntaId = $this->modeloInstancia->crearPregunta($datosPregunta);

            if (!$preguntaId) {
                echo "<script>alert('⚠️ Error al guardar una pregunta'); window.history.back();</script>";
                return;
            }

            // 3. Insertar opciones para cada pregunta
            $yaInsertados = [];
            foreach ($pregunta['opciones'] as $index => $opcion) {
                $clave = $opcion['texto'] . '-' . ($opcion['posicion'] ?? '');

                if (in_array($clave, $yaInsertados)) {
                    continue; // evitar duplicados
                }

                $yaInsertados[] = $clave;

                // tu lógica de $datosOpcion...
                if ($pregunta['tipo'] === 'ordenar') {
                    $datosOpcion = [
                        'id_pregunta' => $preguntaId,
                        'texto' => $opcion['texto'],
                        'es_correcta' => 0,
                        'posicion_correcta' => $opcion['posicion'] ?? null,
                    ];
                } else if ($pregunta['tipo'] === 'multiple-choice') {
                    $esCorrecta = isset($pregunta['correcta']) && (int) $pregunta['correcta'] === $index ? 1 : 0;
                    $datosOpcion = [
                        'id_pregunta' => $preguntaId,
                        'texto' => $opcion['texto'],
                        'es_correcta' => $esCorrecta,
                    ];
                }

                if (!$this->modeloInstancia->crearOpcion($datosOpcion)) {
                    echo "<script>alert('⚠️ Error al guardar una opción'); window.history.back();</script>";
                    return;
                }
            }
        }

        echo "<script>
        alert('✅ Evaluación creada exitosamente');
        window.location.href = '/curso?id={$idCurso}';
        </script>";
    }
    public function resolverEvaluacion()
    {
        $this->validarSesion();

        $idCurso = $_GET['curso'] ?? '';
        if (!$idCurso) {
            echo "<script>alert('⚠️ ID de curso no proporcionado'); window.history.back();</script>";
            return;
        }

        $evaluacion = $this->modeloInstancia->obtenerEvaluacionConPreguntasPorCurso($idCurso);

        if (!$evaluacion || empty($evaluacion['preguntas'])) {
            echo "<script>alert('⚠️ No se encontró una evaluación para este curso'); window.history.back();</script>";
            return;
        }

        $titulo = "PAD - Resolver Evaluación";
        require $this->viewsDir . 'resolver-evaluacion.view.php';
    }

    public function procesarResolverEvaluacion()
    {
        $this->validarSesion();

        $respuestas = $_POST['respuestas'] ?? [];
        $idCurso = $_POST['id_curso'] ?? null;

        if (!$idCurso) {
            echo "<script>alert('⚠️ Curso no especificado'); window.history.back();</script>";
            return;
        }

        $evaluacion = $this->modeloInstancia->obtenerEvaluacionConPreguntasPorCurso($idCurso);

        if (!$evaluacion || empty($evaluacion['preguntas'])) {
            echo "<script>alert('⚠️ No se pudo obtener la evaluación'); window.history.back();</script>";
            return;
        }

        $correctas = 0;
        $totalPreguntas = count($evaluacion['preguntas']);

        foreach ($evaluacion['preguntas'] as $index => $pregunta) {
            if (
                isset($respuestas[$index]) &&
                isset($pregunta['respuesta_correcta']) &&
                $respuestas[$index] === $pregunta['respuesta_correcta']
            ) {
                $correctas++;
            }
        }

        $puntuacion = round(($correctas / $totalPreguntas) * 10);

        $_SESSION['resultado_evaluacion'] = [
            'curso_id' => $idCurso,
            'evaluacion_titulo' => $evaluacion['titulo'],
            'correctas' => $correctas,
            'total' => $totalPreguntas,
            'puntuacion' => $puntuacion
        ];

        header("Location: /resultado-evaluacion");
        exit;
    }

    public function resultadoEvaluacion()
    {
        $this->validarSesion();
        $resultado = $_SESSION['resultado_evaluacion'] ?? null;
        $titulo = "PAD - Resultados";
        unset($_SESSION['resultado_evaluacion']);
        require $this->viewsDir . 'resultados.view.php';
    }

}
