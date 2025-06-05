<?php

namespace PAW\src\App\Controlador;
use PAW\src\Core\Controlador;
use PAW\src\App\Modelos\ColeccionCursos;

class ControladorCursos extends Controlador{
    public ?string $modelo = ColeccionCursos::class;

    public function cursos()
    {
        $titulo = "PAD - Cursos";
        $cursos = $this->modeloInstancia->getAll();
        require $this->viewsDir . 'cursos.view.php';
    }

    public function curso()
    {
        if(!isset($_SESSION['usuario'])){
            echo "<script>alert('⚠️ Debes iniciar sesion para ver un curso'); window.location.href = '/login'</script>";
            return;
        }
        global $request;
        $cursoId = $request->get('id');
        $curso = $this->modeloInstancia->get($cursoId);
        $titulo = htmlspecialchars($curso['titulo'] ?? 'Curso no encontrado');
        require $this->viewsDir . 'curso.view.php';
    }

    public function verUnidad()
    {
        global $request;
        $cursoId = $request->get("idCurso");
        $unidadId = $request->get("idUnidad");
        $unidad = $this->modeloInstancia->getModulo($cursoId, $unidadId);
        $recursoHtml = $this->embedRecurso($unidad['recurso'] ?? '');
        require $this->viewsDir . 'ver-unidad.view.php';
    }

    public function agregarCurso()
    {
        $titulo = "PAD - Agregar Curso";
        require $this->viewsDir . 'agregar-curso.view.php';
    }

    public function procesarAgregarCurso()
    {
        global $request;
        $tituloCurso = $request->get("titulo");
        $descripcionCurso = $request->get("descripcion");
        $creado_por = $_SESSION["usuario"]["id"];
        $nivel = $request->get("nivel");
        $duracion =(int) $request->get("duracion");
        $imagen = $request->get("imagen");

        $datosCurso = [
            'titulo' => $tituloCurso,
            'descripcion' => $descripcionCurso,
            'creado_por' => $creado_por,
            'nivel' => $nivel,
            'duracion' => $duracion,
            'imagen' => $imagen
        ];

        $cursoId = $this->modeloInstancia->crear($datosCurso);

        if(!$cursoId){
            echo "<script>alert('⚠️ Error al crear el curso'); window.history.back();</script>";
            return;
        }
        $temas = $request->get("temario");
        foreach ($temas as $orden => $temaTitulo) {
            $temaDatos = [
                'curso_id' => $cursoId,
                'titulo' => $temaTitulo,
            ];

            if (!$this->modeloInstancia->guardarTema($temaDatos)) {
                echo "<script>alert('⚠️ Error al guardar un tema'); window.history.back();</script>";
                return;
            }
        }

    }

    public function resolverEvaluacion()
    {
        $curso = $this->buscarCursoPorTitulo($_GET['curso'] ?? '');
        $evaluacion = $this->obtenerEvaluacionPorCurso($curso['titulo'] ?? '');
        $titulo = "PAD - Resolver Evaluación";
        require $this->viewsDir . 'resolver-evaluacion.view.php';
    }

    public function procesarResolverEvaluacion()
    {
        $respuestas = $_POST['respuestas'] ?? [];
        $curso = $_POST['curso'] ?? '';
        $evaluacion = $this->obtenerEvaluacionPorCurso($curso);

        $correctas = 0;
        $totalPreguntas = count($evaluacion['preguntas']);

        foreach ($evaluacion['preguntas'] as $index => $pregunta) {
            if (isset($respuestas[$index]) && $respuestas[$index] === $pregunta['respuesta_correcta']) {
                $correctas++;
            }
        }

        // Calcular puntuación del 1 al 10
        $puntuacion = round(($correctas / $totalPreguntas) * 10);

        // Guardar resultado en sesión
        $_SESSION['resultado_evaluacion'] = [
            'curso' => $curso,
            'correctas' => $correctas,
            'total' => $totalPreguntas,
            'puntuacion' => $puntuacion
        ];

        header("Location: /resultado-evaluacion");
        exit;
    }

    public function resultadoEvaluacion()
    {
        $resultado = $_SESSION['resultado_evaluacion'] ?? null;
        $titulo = "PAD - Resultados";
        unset($_SESSION['resultado_evaluacion']);
        require $this->viewsDir . 'resultados.view.php';
    }

    function slugify($text)
    {
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        return trim($text, '-');
    }

    public function embedRecurso(string $rutaOUrl): string
    {
        if (empty($rutaOUrl)) {
            return '<p>No hay recurso para esta unidad.</p>';
        }

        // 1) Detectar YouTube
        if (strpos($rutaOUrl, 'youtube.com/watch?v=') !== false) {
            parse_str(parse_url($rutaOUrl, PHP_URL_QUERY), $params);
            if (isset($params['v'])) {
                $videoId = htmlspecialchars($params['v']);
                return "<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/{$videoId}\" frameborder=\"0\" allowfullscreen></iframe>";
            }
        }
        if (strpos($rutaOUrl, 'youtu.be/') !== false) {
            $videoId = basename(parse_url($rutaOUrl, PHP_URL_PATH));
            $videoId = htmlspecialchars($videoId);
            return "<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/{$videoId}\" frameborder=\"0\" allowfullscreen></iframe>";
        }

        // 2) Si es URL externa cualquiera (que no sea YouTube), devolvemos link
        if (filter_var($rutaOUrl, FILTER_VALIDATE_URL)) {
            // Si la URL termina en .pdf -> embebemos
            $ext = strtolower(pathinfo(parse_url($rutaOUrl, PHP_URL_PATH), PATHINFO_EXTENSION));
            if ($ext === 'pdf') {
                return "<embed src=\"{$rutaOUrl}\" type=\"application/pdf\" width=\"100%\" height=\"600px\" />";
            }
            // Para audio externos
            if (in_array($ext, ['mp3','wav','ogg'])) {
                return "<audio controls src=\"{$rutaOUrl}\">Tu navegador no soporta audio.</audio>";
            }
            // Para imágenes externas
            if (in_array($ext, ['jpg','jpeg','png','gif','webp'])) {
                return "<img src=\"{$rutaOUrl}\" alt=\"Recurso imagen\" style=\"max-width:100%;\" />";
            }
            // Cualquier otro link externo
            return "<p><a href=\"{$rutaOUrl}\" target=\"_blank\" rel=\"noopener\">Ver recurso</a></p>";
        }

        // 3) Si no es URL válida, asumimos que es ruta interna hacia /uploads/ (archivo subido)
        // Verificamos la extensión para decidir cómo mostrarlo.
        $rutaAbsoluta = $_SERVER['DOCUMENT_ROOT'] . $rutaOUrl;
        $ext = strtolower(pathinfo($rutaOUrl, PATHINFO_EXTENSION));

        switch ($ext) {
            case 'pdf':
                return "<embed src=\"{$rutaOUrl}\" type=\"application/pdf\" width=\"100%\" height=\"600px\" />";
            case 'mp3':
            case 'wav':
            case 'ogg':
                return "<audio controls src=\"{$rutaOUrl}\">Tu navegador no soporta audio.</audio>";
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
            case 'webp':
                return "<img src=\"{$rutaOUrl}\" alt=\"Recurso imagen\" style=\"max-width:100%;\" />";
            default:
                // Para cualquier otro tipo (ZIP, DOCX, XLSX, etc.), devolvemos enlace de descarga
                return "<p><a href=\"{$rutaOUrl}\" download>Descargar recurso</a></p>";
        }
    }

    public function obtenerEvaluacionPorCurso(string $nombreCurso)
    {
        foreach ($this->evaluaciones as $evaluacion) {
            if (isset($evaluacion['curso']) && $evaluacion['curso'] === $nombreCurso) {
                return $evaluacion;
            }
        }

        return null; // No se encontró evaluación para ese curso
    }
}
