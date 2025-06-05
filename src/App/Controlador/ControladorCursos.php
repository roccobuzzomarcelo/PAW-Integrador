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
        $creado_por = $_SESSION["usuario"]["id"] ?? null;
        $nivel = $request->get("nivel");
        $duracion = $request->get("duracion");
        $imagen = $request->get("imagen");

        $datosCurso = [
            'titulo' => $tituloCurso,
            'descripcion' => $descripcionCurso,
            'creado_por' => $creado_por,
            'nivel' => $nivel,
            'duracion' => $duracion,
            'imagen' => $imagen
        ];

        if(!$this->modeloInstancia->crear($datosCurso)){
            echo "<script>alert('⚠️ Error al crear el curso'); window.history.back();</script>";
            return;
        }

        $temas = $request->get("temario");
        var_dump($datosCurso, $temas);
        die;


        header("Location: /agregar-unidades");
    }

    public function resolverEvaluacion()
    {
        $curso = $this->buscarCursoPorTitulo($_GET['curso'] ?? '');
        $evaluacion = $this->obtenerEvaluacionPorCurso($curso['titulo'] ?? '');
        $titulo = "PAD - Resolver Evaluación";
        require $this->viewsDir . 'resolver-evaluacion.view.php';
    }
    public function agregarUnidades()
    {
        session_start();
        $actual = $_SESSION['unidad_actual'];
        $max = $_SESSION['curso']['cantidadUnidades'];
        $titulo = "PAD - Agregar Unidades";
        require $this->viewsDir . 'agregar-unidades.view.php';
    }

    public function agregarEvaluacion()
    {
        session_start();
        $titulo = "PAD - Agregar Evaluación";
        require $this->viewsDir . 'agregar-evaluacion.view.php';
    }

   public function procesarAgregarEvaluacion()
    {
        session_start();
        if (!isset($_SESSION['curso'])) {
            die("No hay curso en sesión.");
        }

        $curso = $_SESSION['curso'];
        $tituloCurso = $curso['titulo'];
        $preguntas = $_POST['preguntas'] ?? [];

        // Armamos la evaluación en texto plano
        $datosEvaluacion = "CURSO: " . $tituloCurso . "\n";
        $datosEvaluacion .= "PREGUNTAS:\n";

        $contador = 1;
        foreach ($preguntas as $pregunta) {
            $datosEvaluacion .= "\n";
            $datosEvaluacion .= "PREGUNTA " . $contador . ": " . $pregunta['pregunta'] . "\n";

            $datosEvaluacion .= "OPCIONES:\n";
            $contador2 = 1;
            foreach ($pregunta['opciones'] as $respuesta) {
                $datosEvaluacion .= $contador2 . ") " . $respuesta . "\n";
                $contador2++;
            }

            $datosEvaluacion .= "Respuesta correcta: " . $pregunta['respuesta_correcta'] . "\n";
            $contador++;
        }

        $datosEvaluacion .= "\n---\n";

        // Guardar evaluación en modo texto plano
        $archivoEvaluacion = __DIR__ . "/../../evaluaciones.txt";
        file_put_contents($archivoEvaluacion, $datosEvaluacion, FILE_APPEND);

        // Armar curso en texto plano
        $datosCurso = "TITULO: " . $curso['titulo'] . "\n";
        $datosCurso .= "DESCRIPCION: " . $curso['descripcion'] . "\n";
        $datosCurso .= "TEMARIO: \n" . $curso['temario'] . "\n";
        $datosCurso .= "CANTIDAD_UNIDADES: " . $curso['cantidadUnidades'] . "\n";
        $datosCurso .= "NIVEL: " . $curso['nivel'] . "\n";
        $datosCurso .= "DURACION: " . $curso['duracion'] . "\n";
        $datosCurso .= "IMAGEN: " . $curso['imagen'] . "\n";
        $datosCurso .= "UNIDADES:\n";
        foreach ($curso['unidades'] as $unidad) {
            $datosCurso .= "\n";
            $datosCurso .= "UNIDAD:\n";
            $datosCurso .= "SUBTITULO: " . $unidad['subtitulo'] . "\n";
            $datosCurso .= "DESCRIPCION: " . $unidad['descripcion'] . "\n";
            $datosCurso .= "RECURSO: " . $unidad['recurso'] . "\n";
        }
        $datosCurso .= "\n";
        $datosCurso .= "---\n";
        // Guardar curso
        $archivoCurso = __DIR__ . "/../../cursos.txt";
        file_put_contents($archivoCurso, $datosCurso, FILE_APPEND);

        // Limpiar sesión
        unset($_SESSION['curso'], $_SESSION['unidad_actual']);

        // Redirigir
        header("Location: /cursos");
        exit;
    }
    
    public function procesarAgregarUnidades(){
        session_start();

        // Directorio donde guardaremos los archivos subidos
        $uploadDir = __DIR__ . '/../../public/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $recursoFinal = '';

        // 1) Si subieron un archivo
        if (isset($_FILES['recursoArchivo']) && $_FILES['recursoArchivo']['error'] === UPLOAD_ERR_OK) {
            $archivoTemp   = $_FILES['recursoArchivo']['tmp_name'];
            $nombreOriginal = basename($_FILES['recursoArchivo']['name']);
            // Crear un nombre único para evitar sobreescrituras:
            $extension = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
            $nombreUnico = uniqid('recurso_') . '.' . $extension;
            $destino = $uploadDir . $nombreUnico;

            if (move_uploaded_file($archivoTemp, $destino)) {
                // Guardamos la URL pública (ajusta según tu .htaccess o tu base URL)
                // Por ejemplo, si public/ es la raíz web, podrías usar "/uploads/{$nombreUnico}"
                $recursoFinal = '/uploads/' . $nombreUnico;
            } else {
                // Manejo de error al mover el archivo
                $recursoFinal = '';
            }
        }
        // 2) Si en lugar de archivo, pegaron un enlace
        elseif (!empty($_POST['recursoLink'])) {
            // Sanitizar la URL
            $url = filter_var($_POST['recursoLink'], FILTER_SANITIZE_URL);
            if (filter_var($url, FILTER_VALIDATE_URL)) {
                $recursoFinal = $url;
            } else {
                $recursoFinal = ''; // URL inválida
            }
        }

        // 3) Guardamos en la sesión
        $_SESSION['curso']['unidades'][] = [
            'subtitulo'   => $_POST['subtitulo'],
            'descripcion' => $_POST['descripcion'],
            'recurso'     => $recursoFinal, 
        ];

        // Avanzar de unidad
        $_SESSION['unidad_actual']++;
        if ($_SESSION['unidad_actual'] > $_SESSION['curso']['cantidadUnidades']) {
            header("Location: /agregar-evaluacion");
        } else {
            header("Location: /agregar-unidades");
        }
        exit;
    }


    public function procesarResolverEvaluacion()
    {
        session_start();
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
        session_start();
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

    public function parsearCursos()
    {
        $ruta = __DIR__ . "/../../cursos.txt";
        $cursos = [];

        if (!file_exists($ruta)) {
            return $cursos;
        }

        $contenido = file_get_contents($ruta);
        $bloques = preg_split('/-{3,}\R/', $contenido); // Separador: línea con al menos tres guiones

        foreach ($bloques as $bloque) {
            if (trim($bloque) === '') continue;

            $lineas = explode("\n", trim($bloque));
            $curso = [];
            $unidades = [];
            $unidadActual = [];
            $enTemario = false;

            foreach ($lineas as $linea) {
                $linea = trim($linea);
                if ($linea === '') continue;

                if (str_starts_with($linea, "TITULO:")) {
                    $curso['titulo'] = trim(substr($linea, 7));
                    $enTemario = false;
                } elseif (str_starts_with($linea, "DESCRIPCION:") && !isset($curso['descripcion'])) {
                    $curso['descripcion'] = trim(substr($linea, 12));
                    $enTemario = false;
                } elseif (str_starts_with($linea, "TEMARIO:")) {
                    $curso['temario'] = '';
                    $enTemario = true;
                } elseif (str_starts_with($linea, "CANTIDAD_UNIDADES:")) {
                    $curso['cantidadUnidades'] = (int) trim(substr($linea, 18));
                    $enTemario = false;
                } elseif (str_starts_with($linea, "NIVEL:")) {
                    $curso['nivel'] = trim(substr($linea, 6));
                    $enTemario = false;
                } elseif (str_starts_with($linea, "DURACION:")) {
                    $curso['duracion'] = trim(substr($linea, 9));
                    $enTemario = false;
                } elseif (str_starts_with($linea, "IMAGEN:")) {
                    $curso['imagen'] = trim(substr($linea, 7));
                    $enTemario = false;
                } elseif (str_starts_with($linea, "UNIDAD:")) {
                    if (!empty($unidadActual)) {
                        $unidades[] = $unidadActual;
                        $unidadActual = [];
                    }
                    $enTemario = false;
                } elseif (str_starts_with($linea, "SUBTITULO:")) {
                    $unidadActual['subtitulo'] = trim(substr($linea, 10));
                } elseif (str_starts_with($linea, "DESCRIPCION:")) {
                    $unidadActual['descripcion'] = trim(substr($linea, 12));
                } elseif (str_starts_with($linea, "RECURSO:")) {
                    $unidadActual['recurso'] = trim(substr($linea, 8));
                } elseif ($enTemario) {
                    $curso['temario'] .= ($curso['temario'] ? "\n" : "") . $linea;
                }
            }

            if (!empty($unidadActual)) {
                $unidades[] = $unidadActual;
            }

            $curso['unidades'] = $unidades;
            $cursos[] = $curso;
        }

        return $cursos;
    }


    public function parsearEvaluaciones()
    {
        $archivo = __DIR__ . "/../../evaluaciones.txt";
        $evaluaciones = [];

        if (!file_exists($archivo)) {
            return $evaluaciones;
        }

        $contenido = file_get_contents($archivo);
        $bloques = preg_split('/-{3,}/', $contenido);

        foreach ($bloques as $bloque) {
            $lineas = array_values(array_filter(array_map('trim', explode("\n", $bloque))));
            if (count($lineas) === 0) continue;

            $evaluacion = [
                'curso' => '',
                'preguntas' => []
            ];

            $i = 0;
            while ($i < count($lineas)) {
                $linea = $lineas[$i];

                if (str_starts_with($linea, 'CURSO:')) {
                    $evaluacion['curso'] = trim(substr($linea, strlen('CURSO:')));
                    $i++;
                } elseif (str_starts_with($linea, 'PREGUNTA')) {
                    $preguntaTexto = trim(substr($linea, strpos($linea, ':') + 1));
                    $i++;

                    // Buscar "OPCIONES:"
                    while ($i < count($lineas) && stripos($lineas[$i], 'OPCIONES') === false) {
                        $i++;
                    }
                    $i++; // Saltar la línea "OPCIONES:"

                    // Obtener opciones numeradas
                    $opciones = [];
                    while ($i < count($lineas) && preg_match('/^\d+\)/', $lineas[$i])) {
                        $opciones[] = preg_replace('/^\d+\)\s*/', '', $lineas[$i]);
                        $i++;
                    }

                    // Obtener respuesta correcta
                    $respuesta_correcta = '';
                    if ($i < count($lineas) && str_starts_with($lineas[$i], 'Respuesta correcta:')) {
                        $respuesta_correcta = trim(substr($lineas[$i], strlen('Respuesta correcta:')));
                        $i++;
                    }

                    $evaluacion['preguntas'][] = [
                        'pregunta' => $preguntaTexto,
                        'opciones' => $opciones,
                        'respuesta_correcta' => $respuesta_correcta
                    ];
                } else {
                    $i++;
                }
            }

            if (!empty($evaluacion['curso']) && !empty($evaluacion['preguntas'])) {
                $evaluaciones[] = $evaluacion;
            }
        }

        return $evaluaciones;
    }


    public function buscarCursoPorTitulo(string $tituloBuscado): ?array
    {
        foreach ($this->cursos as $curso) {
            if (strcasecmp($curso['titulo'], $tituloBuscado) === 0) { // compara sin distinguir mayúsculas/minúsculas
                return $curso;
            }
        }
        return null; // No se encontró ningún curso con ese título
    }

    function guardarImagen($inputName = 'imagen', $directorio = 'uploads/')
    {
        // Asegurar que el nombre sea string
        if (!is_string($inputName)) {
            return ''; // Previene errores
        }

        // Verificar existencia y que sea un array
        if (!isset($_FILES[$inputName]) || !is_array($_FILES[$inputName])) {
            return '';
        }

        // Verificar que no haya errores
        if ($_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) {
            return '';
        }

        $ext = pathinfo($_FILES[$inputName]['name'], PATHINFO_EXTENSION);
        $nombreImagen = uniqid('img_') . '.' . $ext;

        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        $rutaDestino = rtrim($directorio, '/') . '/' . $nombreImagen;

        if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $rutaDestino)) {
            return $nombreImagen;
        }

        return ''; // Fallback si falla el movimiento
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
