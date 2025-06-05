<?php

namespace PAW\src\App\Modelos;

use PAW\src\Core\Modelo;
use PAW\src\App\Modelos\Usuario;

class ColeccionUsuarios extends Modelo
{
    public $table = 'usuarios';

    public function crear($datos)
    {
        return $this->queryBuilder->insert($this->table, $datos);
    }

    public function autenticar($email, $password)
    {
        $resultado = $this->queryBuilder->select($this->table, ["correo" => $email]);

        if (empty($resultado)) {
            return null;
        }

        $usuario = new Usuario;
        $usuario->setQueryBuilder($this->queryBuilder);
        $usuario->set($resultado[0]);

        if ($password === $usuario->campos['password']) {
            return $usuario;
        }

        return null;
    }

    public function actualizar($id, $datos)
    {
        return $this->queryBuilder->update($this->table, $datos, ["id" => $id]);
    }

    public function existeEmail($email)
    {
        $resultado = $this->queryBuilder->select($this->table, ["correo" => $email]);
        return !empty($resultado);
    }
}