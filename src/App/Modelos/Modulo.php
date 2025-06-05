<?php

namespace PAW\src\App\Modelos;

use PAW\src\Core\Exceptions\InvalidValueFormatException;
use PAW\src\Core\Modelo;

class Modulo extends Modelo
{
    public $table = 'modulos';

    public $campos = [
        "id" => null,
        "curso_id" => null,
        "titulo" => null,
        "descripcion" => null,
        "tipo" => null,
        "url" => null,
        "orden" => null,
    ];

    public function setId($id)
    {
        if (!is_numeric($id) || intval($id) < 0) {
            throw new InvalidValueFormatException("El ID debe ser un número entero positivo.");
        }
        $this->campos['id'] = intval($id);
    }

    public function setCurso_id($curso_id)
    {
        if (!is_numeric($curso_id) || intval($curso_id) <= 0) {
            throw new InvalidValueFormatException("El curso_id debe ser un número entero positivo.");
        }
        $this->campos['curso_id'] = intval($curso_id);
    }

    public function setTitulo(string $titulo)
    {
        $titulo = trim($titulo);
        if (strlen($titulo) === 0) {
            throw new InvalidValueFormatException("El título no puede estar vacío.");
        }
        $this->campos['titulo'] = $titulo;
    }

    public function setDescripcion(string $descripcion)
    {
        $descripcion = trim($descripcion);
        if (strlen($descripcion) === 0) {
            throw new InvalidValueFormatException("La descripción no puede estar vacía.");
        }
        $this->campos['descripcion'] = $descripcion;
    }

    public function setTipo(string $tipo)
    {
        $tipo = trim($tipo);
        if (strlen($tipo) === 0) {
            throw new InvalidValueFormatException("El tipo no puede estar vacío.");
        }
        $this->campos['tipo'] = $tipo;
    }

    public function setUrl(string $url)
    {
        $url = trim($url);
        if (strlen($url) === 0) {
            throw new InvalidValueFormatException("La URL no puede estar vacía.");
        }
        $this->campos['url'] = $url;
    }

    public function setOrden($orden)
    {
        if (!is_numeric($orden) || intval($orden) < 0) {
            throw new InvalidValueFormatException("El orden debe ser un número entero no negativo.");
        }
        $this->campos['orden'] = intval($orden);
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
