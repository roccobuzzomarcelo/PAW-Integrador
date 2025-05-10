<?php

namespace PAW\src\App\Controlador;

class ControladorPagina
{
    public string $viewsDir;
    public array $menu;
    private array $cursos;
    private array $evaluaciones;

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
        $this->cursos = $this->parsearCursos();
        $this->evaluaciones = $this->parsearevaluaciones();
    }

    public function index()
    {
        $titulo = "PAD - Inicio";
        require $this->viewsDir . 'index.view.php';
    }

    public function cursos()
    {
        $titulo = "PAD - Cursos";
        $cursos = $this->cursos;
        require $this->viewsDir . 'cursos.view.php';
    }

    public function curso()
    {
        $tituloCurso = $_GET['titulo'] ?? '';
        $curso = $this->buscarCursoPorTitulo($tituloCurso);
        $titulo = htmlspecialchars($curso['titulo'] ?? 'Curso no encontrado');
        require $this->viewsDir . 'curso.view.php';
    }

    public function verUnidad()
    {
        $curso = $this->buscarCursoPorTitulo($_GET['curso'] ?? '');
        $unidadIndex = $_GET['unidad'] ?? 0;
        $unidad = $curso['unidades'][$unidadIndex] ?? null;
        $recurso = $this->embedVideo($unidad['recurso'] ?? '');
        require $this->viewsDir . 'ver-unidad.view.php';
    }

    public function resolverEvaluacion()
    {
        $curso = $this->buscarCursoPorTitulo($_GET['curso'] ?? '');
        $evaluacion = $this->obtenerEvaluacionPorCurso($curso['titulo'] ?? '');
        $titulo = "PAD - Resolver Evaluación";
        require $this->viewsDir . 'resolver-evaluacion.view.php';
    }

    public function agregarCurso()
    {
        $titulo = "PAD - Agregar Curso";
        require $this->viewsDir . 'agregar-curso.view.php';
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

    public function faq()
    {
        $titulo = "PAD - FAQ";
        require $this->viewsDir . 'faq.view.php';
    }

    public function procesarLogin()
    {
        // Recoger los datos del formulario
        $email = $_POST['inputEmail'];
        $password = $_POST['inputPassword'];
        $archivo = __DIR__ . "/../../usuarios.txt";

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Dirección de correo electrónico inválida.";
            exit;
        }

        if (!file_exists($archivo)) {
            echo "⚠️ Archivo de usuarios no encontrado.";
            return;
        }

        $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $credencialesValidas = false;

        foreach ($lineas as $linea) {
            list($id, $emailArchivo, $passArchivo, $nombre) = explode('|', trim($linea));
            if ($email === $emailArchivo && $password === $passArchivo) {
                $credencialesValidas = true;
                break;
            }
        }

        if ($credencialesValidas) {
            $this->index();
            exit;
        } else {
            echo "❌ Email o contraseña incorrectos.";
        }
    }

    public function procesarRegistro()
    {
        if (
            empty($_POST['inputNombre']) ||
            empty($_POST['inputEmail']) ||
            empty($_POST['inputPassword']) ||
            empty($_POST['inputConfirmarPassword'])
        ) {
            echo "⚠️ Todos los campos obligatorios deben estar completos.";
            return;
        }
        // Recoger los datos del formulario
        $nombre = $_POST['inputNombre'];
        $email = $_POST['inputEmail'];
        $password = $_POST['inputPassword'];
        $confirmarPassword = $_POST['inputConfirmarPassword'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "⚠️ Email inválido.";
            return;
        }

        if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $nombre)) {
            echo "⚠️ Nombre inválido.";
            return;
        }

        if ($password !== $confirmarPassword) {
            echo "⚠️ Las contraseñas no coinciden.";
            return;
        }

        // Ruta del archivo de texto donde se guardarán los datos
        $archivo = __DIR__ . "/../../usuarios.txt";

        $id = 1; // Si es el primero
        if (file_exists($archivo)) {
            $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            $id = count($lineas) + 1;
        }
        // Formatear los datos en un texto legible
        $datos = "$id|$email|$password|$nombre\n";

        // Guardar los datos en el archivo de texto
        file_put_contents($archivo, $datos, FILE_APPEND); // FILE_APPEND agrega los datos al final del archivo

        $this->index();
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


    public function procesarAgregarCurso()
    {
        session_start();
        $_SESSION['curso'] = [
            'titulo' => $_POST['titulo'],
            'descripcion' => $_POST['descripcion'],
            'temario' => $_POST['temario'],
            'cantidadUnidades' => (int) $_POST['cantidadUnidades'],
            'nivel' => $_POST['nivel'],
            'duracion' => $_POST['duracion'],
            'imagen' => $this->guardarImagen($_FILES['imagen']), // función para mover imagen
            'unidades' => []
        ];

        $_SESSION['unidad_actual'] = 1;

        header("Location: /agregar-unidades");
    }

    public function procesarAgregarUnidades()
    {
        session_start();
        // Guardar los datos de esta unidad
        $_SESSION['curso']['unidades'][] = [
            'subtitulo' => $_POST['subtitulo'],
            'descripcion' => $_POST['descripcion'],
            'recurso' => $_POST['recurso'],
        ];
        // Avanzar de unidad
        $_SESSION['unidad_actual']++;
        if ($_SESSION['unidad_actual'] > $_SESSION['curso']['cantidadUnidades']) {
            // Ir a la evaluación final
            header("Location: /agregar-evaluacion");
        } else {
            // Mostrar siguiente unidad
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

    public function embedVideo(string $url): string
    {
        // Maneja URLs tipo: https://www.youtube.com/watch?v=VIDEOID
        if (strpos($url, 'youtube.com/watch?v=') !== false) {
            parse_str(parse_url($url, PHP_URL_QUERY), $params);
            if (isset($params['v'])) {
                return 'https://www.youtube.com/embed/' . htmlspecialchars($params['v']);
            }
        }

        // Maneja URLs acortadas tipo: https://youtu.be/VIDEOID
        if (strpos($url, 'youtu.be/') !== false) {
            $videoId = basename(parse_url($url, PHP_URL_PATH));
            return 'https://www.youtube.com/embed/' . htmlspecialchars($videoId);
        }

        // Si no es un link de YouTube válido, se devuelve el original
        return $url;
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