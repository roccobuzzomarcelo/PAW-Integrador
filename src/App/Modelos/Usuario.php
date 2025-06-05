<?php

namespace PAW\src\App\Modelos;

use PAW\src\Core\Exceptions\InvalidValueFormatException;
use PAW\src\Core\Modelo;

class Usuario extends Modelo
{
    public $table = 'usuarios';

    public $campos = [
        "id" => null,
        "email" => null,
        "password" => null,
        "nombre" => null,
        "tipo_usuario" => null,
        "fecha_creacion" => null,
    ];

    public function setId($id)
    {
        if (!is_numeric($id) || intval($id) < 0) {
            throw new InvalidValueFormatException("El ID debe ser un número entero positivo.");
        }
        $this->campos['id'] = intval($id);
    }

    public function setNombre(string $nombre)
    {
        $nombre = trim($nombre);
        if (strlen($nombre) === 0) {
            throw new InvalidValueFormatException("El nombre no puede estar vacío.");
        }
        if (strlen($nombre) > 50) {
            throw new InvalidValueFormatException("El nombre no puede tener más de 50 caracteres.");
        }
        $this->campos['nombre'] = $nombre;
    }

    public function setEmail(string $email)
    {
        $email = trim($email);
        if (strlen($email) > 100) {
            throw new InvalidValueFormatException("El email no puede tener más de 100 caracteres.");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidValueFormatException("El formato del email no es válido.");
        }
        $this->campos['email'] = $email;
    }

    public function setPassword(string $password)
    {
        if (strlen($password) < 8) {
            throw new InvalidValueFormatException("La contraseña debe tener al menos 8 caracteres.");
        }
        if (strlen($password) > 255) {
            throw new InvalidValueFormatException("La contraseña no puede tener más de 255 caracteres.");
        }
        $this->campos['password'] = $password;
    }

    public function setTipo_usuario(string $rol)
    {
        $rol = strtolower(trim($rol));
        if (!in_array($rol, ['usuario', 'admin'])) {
            throw new InvalidValueFormatException("El rol debe ser 'usuario' o 'admin'.");
        }
        $this->campos['tipo_usuario'] = $rol;
    }

    public function setFecha_creacion(string $fecha)
    {
        $dt = \DateTime::createFromFormat('Y-m-d', $fecha);
        if (!$dt || $dt->format('Y-m-d') !== $fecha) {
            throw new InvalidValueFormatException("La fecha de creación debe tener el formato 'YYYY-MM-DD'.");
        }
        $this->campos['fecha_creacion'] = $fecha;
    }

    public function set(array $valores)
    {
        foreach (array_keys($this->campos) as $campo) {
            if (!isset($valores[$campo])) {
                continue;
            }
            $metodo = "set" . str_replace(' ', '', ucwords(str_replace('_', ' ', $campo)));
            if (method_exists($this, $metodo)) {
                $this->$metodo($valores[$campo]);
            }
        }
    }
}
