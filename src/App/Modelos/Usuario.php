<?php

namespace PAW\src\App\Modelos;

use PAW\src\Core\Exceptions\InvalidValueFormatException;
use PAW\src\Core\Modelo;

class Usuario extends Modelo
{
    public $table = 'usuarios';
    public $campos = [
        "id" => null,
        "nombre" => null,
        "email" => null,
        "password" => null,
        "rol" => null,
        "fecha_creacion" => null,
        "activo" => null,
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
        if (strlen($nombre) > 50) {
            throw new InvalidValueFormatException("El nombre no puede tener más de 50 caracteres.");
        }
        $this->campos['nombre'] = $nombre;
    }

    public function setEmail(string $email)
    {
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
        if (strlen($password) > 255) {
            throw new InvalidValueFormatException("La contraseña no puede tener más de 255 caracteres.");
        }
        if (strlen($password) < 8) {
            throw new InvalidValueFormatException("La contraseña debe tener al menos 8 caracteres.");
        }
        $this->campos['password'] = $password;
    }

    public function setRol(string $rol)
    {
        $rol = strtolower($rol);
        if (!in_array($rol, ['usuario', 'admin'])) {
            throw new InvalidValueFormatException("El rol debe ser 'usuario' o 'admin'.");
        }
        $this->campos['rol'] = $rol;
    }

    public function setFecha_creacion(string $fecha)
    {
        $this->campos['fecha_creacion'] = $fecha;
    }

    public function setActivo($activo)
    {
        // Se permite booleano o valores tipo string/número que representen true/false
        $valor = filter_var($activo, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        if (is_null($valor)) {
            throw new InvalidValueFormatException("El campo 'activo' debe ser un valor booleano.");
        }
        $this->campos['activo'] = $valor;
    }

    public function set(array $valores)
    {
        foreach (array_keys($this->campos) as $campo) {
            if (!isset($valores[$campo])) {
                continue;
            }
            $metodo = "set" . ucfirst($campo);
            $this->$metodo($valores[$campo]);
        }
    }
}