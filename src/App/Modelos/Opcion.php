<?php

namespace PAW\src\App\Modelos;

use PAW\src\Core\Modelo;
use PAW\src\Core\Exceptions\InvalidValueFormatException;

class Opcion extends Modelo
{
    public $table = 'opciones';

    public $campos = [
        'id' => null,
        'id_pregunta' => null,
        'texto' => null,
        'es_correcta' => false,
        'posicion_correcta' => null,
    ];

    public function set(array $datos)
    {
        foreach ($datos as $clave => $valor) {
            if (array_key_exists($clave, $this->campos)) {
                $this->campos[$clave] = $valor;
            }
        }
    }

    public function guardar()
    {
        $datos = $this->campos;
        unset($datos['id']);
        $id = $this->queryBuilder->insert($this->table, $datos);
        $this->campos['id'] = $id;
        return $id;
    }

    public function setIdPregunta(int $id)
    {
        $this->campos['id_pregunta'] = $id;
    }
}
