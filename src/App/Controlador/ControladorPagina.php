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

        // Armamos la nueva evaluación con referencia al curso
        $nuevaEvaluacion = [
            'curso' => $tituloCurso,
            'preguntas' => $preguntas
        ];

        // Ruta del archivo
        $archivoEvaluacion = __DIR__ . "/../../evaluaciones.txt";

        // Cargar evaluaciones anteriores (si existen)
        $evaluaciones = file_exists($archivoEvaluacion) ? json_decode(file_get_contents($archivoEvaluacion), true) : [];

        // Agregar la nueva evaluación
        $evaluaciones[] = $nuevaEvaluacion;

        // Guardar en el JSON
        file_put_contents($archivoEvaluacion, json_encode($evaluaciones, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // Obtener el curso actual de la sesión
        $curso = $_SESSION['curso'];

        // Crear un "slug" del título para el archivo
        $archivo = __DIR__ . "/../../cursos/cursos.txt"; // Archivo único para todos los cursos

        // Si el archivo JSON ya existe, cargar los cursos actuales
        if (file_exists($archivo)) {
            $cursos = json_decode(file_get_contents($archivo), true);
            if (!is_array($cursos)) {
                $cursos = []; // Si no es un array válido, iniciar como array vacío
            }
        } else {
            $cursos = []; // Si no existe el archivo, inicializar como array vacío
        }

        // Agregar el nuevo curso al array de cursos
        $cursos[] = $curso; // Añadir el curso actual al array

        // Guardar todos los cursos nuevamente en el archivo JSON
        file_put_contents($archivo, json_encode($cursos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // Limpiar la sesión del curso
        unset($_SESSION['curso'], $_SESSION['unidad_actual']);

        // Redirigir o mostrar mensaje
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
        $dirCursos = __DIR__ . "/../../cursos/";
        $cursos = [];

        if (is_dir($dirCursos)) {
            foreach (glob($dirCursos . "*.txt") as $archivo) {
                $datos = json_decode(file_get_contents($archivo), true);

                if ($datos === null) {
                    continue;
                }

                $cursos = array_merge($cursos, $datos);
            }
        }

        return $cursos;
    }

    public function parsearevaluaciones()
    {
        $dirEvaluaciones = __DIR__ . "/../../evaluaciones.txt";
        $evaluaciones = [];

        if (file_exists($dirEvaluaciones)) {
            $evaluaciones = json_decode(file_get_contents($dirEvaluaciones), true);
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