<?php

namespace PAW\src\App\Controlador;

use PAW\src\Core\Controlador;
use PAW\src\App\Modelos\ColeccionUsuarios;

class ControladorUsuarios extends Controlador
{
    public ?string $modelo = ColeccionUsuarios::class;

    public function login()
    {
        global $request;
        $datos = $_SESSION["usuario"] ?? null;
        if (!is_null($datos)) {
            $this->userProfile();
            return;
        }
        $htmlClass = "mi-cuenta-pages";
        $titulo = "PAD - Login";
        require $this->viewsDir . 'login.view.php';
    }

    public function userProfile()
    {
        $usuario = $_SESSION['usuario'];
        $fecha = date("d/m/Y", strtotime($usuario["fecha_creacion"]));
        $titulo = 'PAD - Mi cuenta';
        $htmlClass = "mi-cuenta-pages";
        require $this->viewsDir . 'user-profile.view.php';
    }

    public function logout()
    {
        session_unset();           // Limpiamos todas las variables de sesión
        session_destroy();         // Destruimos la sesión
        echo "<script>
            alert('✅ Sesión cerrada exitosamente');
            window.location.href = '/';
        </script>";
    }

    public function procesarLogin()
    {
        global $request;
        // Recoger los datos del formulario
        $email = $request->get('inputEmail');
        $password = $request->get('inputPassword');

        // Validación mínima de formato
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('⚠️ Email no valido'); window.history.back();</script>";
            return;
        }
        if (empty($password)) {
            echo "<script>alert('⚠️ La contraseña no puede estar vacia'); window.history.back();</script>";
            return;
        }
        if (strlen($password) < 8) {
            echo "<script>alert('⚠️ La contraseña debe tener al menos 8 caracteres'); window.history.back();</script>";
            return;
        }

        $usuario = $this->modeloInstancia->autenticar($email, $password);
        if (empty($usuario)) {
            echo "<script>alert('⚠️ Email o contraseña incorrectos'); window.history.back();</script>";
            return;
        }
        $_SESSION['usuario'] = $usuario->campos;
        // Éxito: redirigir a página principal u otra
        echo "<script>
            alert('✅ Sesion iniciada exitosamente');
            window.location.href = '/';
        </script>";
        exit();
    }

    public function register()
    {
        $titulo = "PAD - Registro";
        $htmlClass = "mi-cuenta-pages";
        require $this->viewsDir . 'register.view.php';
    }

    public function procesarRegistro()
    {
        global $request;
        if (
            empty($request->get('inputNombre')) ||
            empty($request->get('inputEmail')) ||
            empty($request->get('inputPassword')) ||
            empty($request->get('inputConfirmarPassword'))
        ) {
            echo "<script>alert('⚠️ Todos los campos obligatorios deben completarse'); window.history.back();</script>";
            return;
        }
        // Recoger los datos del formulario
        $nombre = $request->get('inputNombre');
        $email = $request->get('inputEmail');
        $password = $request->get('inputPassword');
        $confirmarPassword = $request->get('inputConfirmarPassword');
        $datos = [
            'nombre' => $nombre,
            'correo' => $email,
            'password' => $password,
        ];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('⚠️ Email no valido'); window.history.back();</script>";
            return;
        }

        if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $nombre)) {
            echo "<script>alert('⚠️ Nombre no valido'); window.history.back();</script>";
            return;
        }

        if (strlen($password) < 8) {
            echo "<script>alert('⚠️ La contraseña debe tener al menos 8 caracteres'); window.history.back();</script>";
            return;
        }

        if ($password !== $confirmarPassword) {
            echo "<script>alert('⚠️ Las contraseña no coinciden'); window.history.back();</script>";
            return;
        }

        if ($this->modeloInstancia->existeEmail($email)) {
            echo "<script>alert('⚠️ El Email ya esta registrado'); window.history.back();</script>";
            return;
        }

        // Crear un nuevo usuario
        if (!$this->modeloInstancia->crear($datos)) {
            echo "<script>alert('⚠️ Error al crear el usuario'); window.history.back();</script>";
            return;
        }
        // Éxito: redirigir a página principal u otra
        echo "<script>
            alert('✅ Registro exitoso');
            window.location.href = '/user-profile';
        </script>";
    }

    public function editarUsuario()
    {
        $datos = $_SESSION['usuario'];
        $titulo = 'PAWPrints - Editar usuario';
        $htmlClass = "mi-cuenta-pages";
        require $this->viewsDir . 'editar-usuario.view.php';
    }

    public function procesarEditarUsuario()
    {
        global $request;
        if (
            empty($request->get('password')) ||
            empty($request->get('confirmar_password'))
        ) {
            echo "<script>alert('⚠️ Todos los campos obligatorios deben completarse'); window.history.back();</script>";
            return;
        }

        $nombre = $request->get('inputNombre');
        if (empty($nombre)) {
            $nombre = $_SESSION['usuario']['nombre'];
        }
        $email = $request->get('inputEmail');
        if (empty($email)) {
            $email = $_SESSION['usuario']['email'];
        }
        $password = $request->get('password');
        $confirmarPassword = $request->get('confirmar_password');
        $datos = [
            'nombre' => $nombre,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('⚠️ Email no valido'); window.history.back();</script>";
            return;
        }

        if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $nombre)) {
            echo "<script>alert('⚠️ Nombre no valido'); window.history.back();</script>";
            return;
        }

        if (strlen($password) < 8) {
            echo "<script>alert('⚠️ La contraseña debe tener al menos 8 caracteres'); window.history.back();</script>";
            return;
        }

        if ($password !== $confirmarPassword) {
            echo "<script>alert('⚠️ Las contraseña no coinciden'); window.history.back();</script>";
            return;
        }

        // Actualizar el usuario
        if (!$this->modeloInstancia->actualizar($_SESSION['usuario']['id'], $datos)) {
            echo "<script>alert('⚠️ Error al actualizar el usuario'); window.history.back();</script>";
            return;
        }

        $_SESSION['usuario'] = array_merge($_SESSION['usuario'], $datos);
        // Éxito: redirigir a página principal u otra
        echo "<script>
            alert('✅ Usuario actualizado exitosamente');
            window.location.href = '/mi-cuenta';
        </script>";
    }

    public function recuperarContraseña()
    {
        $titulo = 'PAWPrints - Recuperar contraseña';
        $htmlClass = "mi-cuenta-pages";
        require $this->viewsDir . 'recuperar-contraseña.view.php';
    }

    public function procesarRecuperarContraseña()
    {
        // Recoger los datos del formulario
        $email = $_POST['inputEmail'];
        $archivo = __DIR__ . "/../../login.txt";

        $lineas = file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $emailEncontrado = false;

        foreach ($lineas as $linea) {
            list($id, $emailArchivo, $passArchivo, $nombre, $apellido) = explode('|', trim($linea));
            if ($email === $emailArchivo) {
                $emailEncontrado = true;
                break;
            }
        }

        if ($emailEncontrado) {
            echo "✅ Se ha enviado un enlace para restablecer tu contraseña a tu correo electrónico.";
        } else {
            echo "❌ El email no está registrado.";
        }
    }
}