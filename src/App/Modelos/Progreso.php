<?php

namespace PAW\src\App\Modelos;

use PAW\src\Core\Exceptions\InvalidValueFormatException;
use PAW\src\Core\Modelo;

class Progreso extends Modelo
{
    public $table = 'progresos';

    public $campos = [
        "id" => null,
        "usuario_id" => null,
        "curso_id" => null,
        "modulo_id" => null,
        "completado" => null,
        "fecha_completado" => null,
    ];

    public function setId($id)
    {
        if (!is_numeric($id) || intval($id) < 0) {
            throw new InvalidValueFormatException("El ID debe ser un número entero positivo.");
        }
        $this->campos['id'] = intval($id);
    }

    public function setUsuario_id($usuario_id)
    {
        if (!is_numeric($usuario_id) || intval($usuario_id) <= 0) {
            throw new InvalidValueFormatException("El usuario_id debe ser un número entero positivo.");
        }
        $this->campos['usuario_id'] = intval($usuario_id);
    }

    public function setCurso_id($curso_id)
    {
        if (!is_numeric($curso_id) || intval($curso_id) <= 0) {
            throw new InvalidValueFormatException("El curso_id debe ser un número entero positivo.");
        }
        $this->campos['curso_id'] = intval($curso_id);
    }

    public function setModulo_id($modulo_id)
    {
        if (!is_numeric($modulo_id) || intval($modulo_id) <= 0) {
            throw new InvalidValueFormatException("El modulo_id debe ser un número entero positivo.");
        }
        $this->campos['modulo_id'] = intval($modulo_id);
    }

    public function setCompletado($completado)
    {
        $this->campos['completado'] = filter_var($completado, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        if (!is_bool($this->campos['completado'])) {
            throw new InvalidValueFormatException("El campo 'completado' debe ser booleano.");
        }
    }

    public function setFecha_completado($fecha)
    {
        $dt = \DateTime::createFromFormat('Y-m-d', $fecha);
        if (!$dt || $dt->format('Y-m-d') !== $fecha) {
            throw new InvalidValueFormatException("La fecha de completado debe tener el formato 'YYYY-MM-DD'.");
        }
        $this->campos['fecha_completado'] = $fecha;
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

    public function load($id)
    {
        $parametros = ['id' => $id];
        $resultado = $this->queryBuilder->select($this->table, $parametros);
        $record = current($resultado);
        if ($record) {
            $this->set($record);
        }
    }
}
