<?php

namespace PAW\src\App\Controlador;
use PAW\src\Core\Controlador;
use PAW\src\App\Modelos\ColeccionCursos;

class ControladorCursos extends Controlador
{
    public ?string $modelo = ColeccionCursos::class;

    public function cursos()
    {
        $titulo = "PAD - Cursos";
        $cursos = $this->modeloInstancia->getAll();
        $permiso = $this->validarAdmin();
        require $this->viewsDir . 'cursos.view.php';
    }

    public function validarSesion()
    {
        if (!isset($_SESSION['usuario'])) {
            echo "<script>alert('⚠️ Debes iniciar sesion para ver un curso'); window.location.href = '/login'</script>";
            return;
        }
    }

    public function validarAdmin(): bool
    {
        if (!isset($_SESSION['usuario'])) {
            return false;
        }

        $tipo_usuario = $_SESSION['usuario']['tipo_usuario'] ?? null;

        return $tipo_usuario === 'admin';
    }


    public function curso()
    {
        $this->validarSesion();
        global $request;
        $cursoId = $request->get('id');
        $curso = $this->modeloInstancia->get($cursoId);
        $temas = $this->modeloInstancia->getTemasCurso($cursoId);
        $modulos = $this->modeloInstancia->getModulosCurso($cursoId);
        $usuarioId = $_SESSION["usuario"]["id"];

        foreach ($modulos as &$modulo) {
            $existe = $this->modeloInstancia->existeProgreso($usuarioId, $cursoId, $modulo['id']);
            if (!$existe) {
                $this->modeloInstancia->crearProgreso($usuarioId, $cursoId, $modulo['id']);
                $modulo["completado"] = false;
            } else {
                $modulo["completado"] = $this->modeloInstancia->estaCompletado($usuarioId, $cursoId, $modulo["id"]);
            }
        }
        unset($modulo);
        $titulo = htmlspecialchars($curso->campos['titulo'] ?? 'Curso no encontrado');
        require $this->viewsDir . 'curso.view.php';
    }

    public function verUnidad()
    {
        $this->validarSesion();
        global $request;
        $moduloId = $request->get("modulo");
        $modulo = $this->modeloInstancia->getModulo($moduloId);
        $cursoId = $modulo["curso_id"];
        $usuarioId = $_SESSION["usuario"]["id"];
        $contenido = $this->embedRecurso($modulo["tipo"], $modulo["url"]);
        $this->modeloInstancia->marcarCompletado($moduloId, $cursoId, $usuarioId);
        require $this->viewsDir . 'ver-unidad.view.php';
    }

    public function agregarCurso()
    {
        $this->validarAdmin();
        $titulo = "PAD - Agregar Curso";
        require $this->viewsDir . 'agregar-curso.view.php';
    }
    public function procesarAgregarCurso()
    {
        $this->validarAdmin();
        global $request;
        $tituloCurso = $request->get("titulo");
        $descripcionCurso = $request->get("descripcion");
        $creado_por = $_SESSION["usuario"]["id"];
        $nivel = $request->get("nivel");
        $duracion = (int) $request->get("duracion");

        // Imagen del curso
        $imagenCurso = $_FILES['imagen']['name'] ?? null;
        $rutaImagenCurso = null;
        $carpetaImagenes = __DIR__ . '/../../../public/uploads/';

        if ($imagenCurso && $_FILES['imagen']['error'] === 0) {
            $destinoImagen = $carpetaImagenes . basename($_FILES['imagen']['name']);
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $destinoImagen)) {
                $rutaImagenCurso = '/uploads/' . basename($_FILES['imagen']['name']);
            }
        }

        $datosCurso = [
            'titulo' => $tituloCurso,
            'descripcion' => $descripcionCurso,
            'creado_por' => $creado_por,
            'nivel' => $nivel,
            'duracion' => $duracion,
            'imagen' => $rutaImagenCurso
        ];

        $cursoId = $this->modeloInstancia->crear($datosCurso);

        if (!$cursoId) {
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

        $modulos = $request->get("modulos");
        $archivosModulo = $_FILES['modulos'] ?? [];

        $i = 0;
        foreach ($modulos as $indice => $modulo) {
            $contenidoTipo = null;
            $contenidoUrl = null;

            // Verificar si es un link
            if (!empty($modulo['link'])) {
                $contenidoTipo = 'link';
                $contenidoUrl = $modulo['link'];
            }
            // Si no es link, verificar si se subió un archivo
            elseif (
                !empty($archivosModulo['name'][$indice]['archivo']) &&
                $archivosModulo['error'][$indice]['archivo'] === 0
            ) {

                $nombreArchivo = basename($archivosModulo['name'][$indice]['archivo']);
                $tmpArchivo = $archivosModulo['tmp_name'][$indice]['archivo'];
                $rutaDestino = $carpetaImagenes . $nombreArchivo;

                if (move_uploaded_file($tmpArchivo, $rutaDestino)) {
                    $contenidoTipo = 'archivo';
                    $contenidoUrl = '/uploads/' . $nombreArchivo;
                }
            }

            $datosModulo = [
                "curso_id" => $cursoId,
                "titulo" => $modulo["titulo"],
                "descripcion" => $modulo["descripcion"],
                "tipo" => $this->detectarTipoRecurso($contenidoUrl),
                "url" => $contenidoUrl,
                "orden" => $i + 1
            ];

            if (!$this->modeloInstancia->guardarModulos($datosModulo)) {
                echo "<script>alert('⚠️ Error al guardar un módulo'); window.history.back();</script>";
                return;
            }

            $i++;
        }
        echo "<script>
            alert('✅ Curso guardado exitosamente');
            window.location.href = '/cursos';
        </script>";
    }

    public function modeloIA()
    {
        header("Content-Type: application/json");

        $input = json_decode(file_get_contents("php://input"), true);

        $titulo = trim($input["titulo"] ?? "");
        $descripcion = trim($input["descripcion"] ?? "");
        $temario = $input["temario"] ?? [];

        if (!$titulo || !$descripcion || empty($temario)) {
            echo json_encode(["error" => "Faltan datos para procesar."]);
            return;
        }

        global $config;

        $prompt = "Actuá como un asistente pedagógico especializado en diseño de cursos.

        Tu tarea es analizar la siguiente información sobre un curso y sugerir contenidos complementarios que puedan enriquecerlo. Las recomendaciones deben ser recursos concretos como libros, artículos, videos, podcasts, sitios web, etc.

        Cada recomendación debe tener:
        - un campo \"tipo\" (por ejemplo: \"libro\", \"video\", etc),
        - un campo \"titulo\" (obligatorio),
        - y un campo \"descripcion\" (opcional).

        Es importante que el título esté claramente separado para facilitar el procesamiento posterior.

        ⚠️ Respondé **únicamente con JSON válido**, sin ningún texto antes o después.

        Formato exacto:
        {
            \"recomendaciones\": [
                {
                \"tipo\": \"libro\" | \"video\" | \"artículo\" | \"podcast\" | \"sitio web\",
                \"titulo\": \"Título del recurso\",
                \"descripcion\": \"(opcional) Breve descripción del recurso\"
                },
                ...
            ]
        }

        Datos del curso:
        Título: \"$titulo\"
        Descripción: \"$descripcion\"
        Temario: " . implode(", ", $temario);

        $apiKey = $config->get('IA_KEY') ?? null;

        $url = "https://openrouter.ai/api/v1/chat/completions";

        $data = [
            "model" => "meta-llama/llama-3-8b-instruct",
            "messages" => [
                [
                    "role" => "system",
                    "content" => "Sos un asistente que sugiere contenidos complementarios útiles para cursos educativos."
                ],
                [
                    "role" => "user",
                    "content" => $prompt
                ]
            ]
        ];

        $headers = [
            "Authorization: Bearer $apiKey",
            "Content-Type: application/json",
            "HTTP-Referer: http://tusitio.com", // reemplazar con tu dominio real
            "X-Title: RecomendacionesCurso"
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || !$response) {
            echo json_encode(["error" => "Error al consultar IA (código $httpCode)"]);
            return;
        }

        $json = json_decode($response, true);
        $content = $json["choices"][0]["message"]["content"] ?? null;

        if (!$content) {
            echo json_encode(["error" => "Respuesta de IA no válida."]);
            return;
        }

        // Intentar decodificar el JSON que devuelve el modelo
        $parsed = json_decode($content, true);

        file_put_contents("respuesta_ia.txt", $content);


        if (!is_array($parsed) || !isset($parsed["recomendaciones"])) {
            echo json_encode(["error" => "La IA no devolvió un JSON válido o no contiene recomendaciones."]);
            return;
        }

        // Opcional: validamos que cada ítem tenga al menos tipo y titulo
        $recomendaciones = array_filter($parsed["recomendaciones"], function($rec) {
            return isset($rec["tipo"]) && isset($rec["titulo"]);
        });

        echo json_encode(["recomendaciones" => array_values($recomendaciones)]);
    }



    public function detectarTipoRecurso(string $rutaOUrl): string
    {
        if (empty($rutaOUrl)) {
            return 'vacio';
        }

        // 1) YouTube
        if (strpos($rutaOUrl, 'youtube.com/watch?v=') !== false || strpos($rutaOUrl, 'youtu.be/') !== false) {
            return 'youtube';
        }

        // 2) URL externa
        if (filter_var($rutaOUrl, FILTER_VALIDATE_URL)) {
            $ext = strtolower(pathinfo(parse_url($rutaOUrl, PHP_URL_PATH), PATHINFO_EXTENSION));

            if ($ext === 'pdf')
                return 'pdf';
            if (in_array($ext, ['mp3', 'wav', 'ogg']))
                return 'audio';
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                return 'imagen';

            return 'url'; // URL externa genérica
        }

        // 3) Ruta interna (archivo subido al servidor)
        $ext = strtolower(pathinfo($rutaOUrl, PATHINFO_EXTENSION));

        if ($ext === 'pdf')
            return 'pdf';
        if (in_array($ext, ['mp3', 'wav', 'ogg']))
            return 'audio';
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
            return 'imagen';

        return 'archivo'; // Otro tipo de archivo (ej: zip, docx...)
    }

    public function embedRecurso(string $tipo, string $rutaOUrl): string
    {
        if ($tipo === 'vacio') {
            return '<p>No hay recurso para esta unidad.</p>';
        }

        switch ($tipo) {
            case 'youtube':
                // Soporte para ambos formatos
                if (strpos($rutaOUrl, 'youtube.com/watch?v=') !== false) {
                    parse_str(parse_url($rutaOUrl, PHP_URL_QUERY), $params);
                    $videoId = htmlspecialchars($params['v'] ?? '');
                } else {
                    $videoId = htmlspecialchars(basename(parse_url($rutaOUrl, PHP_URL_PATH)));
                }
                return "<iframe width=\"560\" height=\"315\" src=\"https://www.youtube.com/embed/{$videoId}\" frameborder=\"0\" allowfullscreen></iframe>";

            case 'pdf':
                return "<embed src=\"{$rutaOUrl}\" type=\"application/pdf\" width=\"100%\" height=\"600px\" />";

            case 'audio':
                return "<audio controls src=\"{$rutaOUrl}\">Tu navegador no soporta audio.</audio>";

            case 'imagen':
                return "<img src=\"{$rutaOUrl}\" alt=\"Recurso imagen\" style=\"max-width:100%;\" />";

            case 'url':
                return "<p><a href=\"{$rutaOUrl}\" target=\"_blank\" rel=\"noopener\">Ver recurso</a></p>";

            case 'archivo':
                return "<p><a href=\"{$rutaOUrl}\" download>Descargar recurso</a></p>";

            default:
                return "<p><a href=\"{$rutaOUrl}\" download>Descargar recurso</a></p>";
        }
    }
}
