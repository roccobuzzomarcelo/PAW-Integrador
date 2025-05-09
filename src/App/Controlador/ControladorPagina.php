<?php

namespace PAW\src\App\Controlador;

class ControladorPagina
{
    public string $viewsDir;
    public array $menu;
    private array $cursos;

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
        if(
            empty($_POST['inputNombre']) ||
            empty($_POST['inputEmail']) ||
            empty($_POST['inputPassword']) ||
            empty($_POST['inputConfirmarPassword'])
        ){
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

    public function procesarAgregarCurso() {
        // Sanitizar campos
        $titulo = trim($_POST['titulo']);
        $descripcion = trim($_POST['descripcion']);
        $temario = trim($_POST['temario']);
        $recursos = trim($_POST['recursos']);
        $ejercicios = trim($_POST['ejercicios']);
        $evaluacion = trim($_POST['evaluacion']);
        $nivel = $_POST['nivel'] ?? 'básico';
        $duracion = $_POST['duracion'] ?? '';
        $estado = $_POST['estado'] ?? 'borrador';

        // Manejo de imagen
        $nombreImagen = '';
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
            $nombreImagen = uniqid('img_') . '.' . $ext;
            $uploadsDir = "uploads/";
            if (!is_dir($uploadsDir)) {
                mkdir($uploadsDir, 0777, true);
            }
            move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadsDir . $nombreImagen);
        }

        // Preparar curso
        $nuevoCurso = [
            'titulo' => $titulo,
            'descripcion' => $descripcion,
            'temario' => $temario,
            'imagen' => $nombreImagen,
            'recursos' => $recursos,
            'ejercicios' => $ejercicios,
            'evaluacion' => $evaluacion,
            'nivel' => $nivel,
            'duracion' => $duracion,
            'estado' => $estado,
        ];

        $archivoCursos = __DIR__ . "/../../cursos/cursos.json";

        // Leer cursos existentes
        $cursos = [];
        if (file_exists($archivoCursos)) {
            $contenido = file_get_contents($archivoCursos);
            $cursos = json_decode($contenido, true);
            if (!is_array($cursos)) {
                $cursos = []; // Si el JSON está mal, empezamos desde cero
            }
        }

        // Agregar nuevo curso
        $cursos[] = $nuevoCurso;

        // Guardar el JSON actualizado
        file_put_contents($archivoCursos, json_encode($cursos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // Redirigir o continuar flujo
        $this->cursos();
    }

    function slugify($text) {
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);
        return trim($text, '-');
    }

    public function parsearCursos() {
        $dirCursos = __DIR__ . "/../../cursos/";
        $cursos = [];

        if (is_dir($dirCursos)) {
            foreach (glob($dirCursos . "*.json") as $archivo) {
                $datos = json_decode(file_get_contents($archivo), true);

                if ($datos === null) {
                    continue;
                }

                $cursos = array_merge($cursos, $datos);
            }
        }

        return $cursos;
    }

    public function buscarCursoPorTitulo(string $tituloBuscado): ?array {
        foreach ($this->cursos as $curso) {
            if (strcasecmp($curso['titulo'], $tituloBuscado) === 0) { // compara sin distinguir mayúsculas/minúsculas
                return $curso;
            }
        }
        return null; // No se encontró ningún curso con ese título
    }
}