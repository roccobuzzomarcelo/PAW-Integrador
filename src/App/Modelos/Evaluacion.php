<?php

namespace PAW\src\App\Modelos;

use PAW\src\Core\Exceptions\InvalidValueFormatException;
use PAW\src\Core\Modelo;

class Evaluacion extends Modelo
{
    public $table = 'evaluaciones';

    public $campos = [
        "id" => null,
        "curso_id" => null,
        "tipo" => null,
        "contenido" => null,
        "solucion_correcta" => null,
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

    public function setTipo(string $tipo)
    {
        $tipo = trim($tipo);
        if ($tipo === '') {
            throw new InvalidValueFormatException("El tipo no puede estar vacío.");
        }
        $this->campos['tipo'] = $tipo;
    }

    public function setContenido(string $contenido)
    {
        $contenido = trim($contenido);
        if ($contenido === '') {
            throw new InvalidValueFormatException("El contenido no puede estar vacío.");
        }
        $this->campos['contenido'] = $contenido;
    }

    public function setSolucion_correcta($solucion_correcta)
    {
        $this->campos['solucion_correcta'] = $solucion_correcta;
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

    public function load($id){
        $parametros = ['id' => $id]; // convertimos a array
        $resultado = $this->queryBuilder->select($this->table, $parametros);
        $record = current($resultado);
        $this->set($record);
    }
}
