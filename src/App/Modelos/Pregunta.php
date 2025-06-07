<?php
namespace PAW\src\App\Modelos;

use PAW\src\Core\Modelo;
use PAW\src\Core\Exceptions\InvalidValueFormatException;

class Pregunta extends Modelo
{
    public $table = 'preguntas';

    public $campos = [
        'id' => null,
        'id_evaluacion' => null,
        'tipo' => null,
        'enunciado' => null,
    ];

    private array $opciones = [];

    public function set(array $datos)
    {
        foreach ($datos as $clave => $valor) {
            if (array_key_exists($clave, $this->campos)) {
                $this->campos[$clave] = $valor;
            }
        }
    }

    public function setIdEvaluacion(int $id)
    {
        $this->campos['id_evaluacion'] = $id;
    }

    public function agregarOpcion(Opcion $opcion)
    {
        $this->opciones[] = $opcion;
    }

    public function guardar()
    {
        $datos = $this->campos;
        unset($datos['id']);
        $id = $this->queryBuilder->insert($this->table, $datos);
        $this->campos['id'] = $id;

        // Guardar opciones
        foreach ($this->opciones as $opcion) {
            $opcion->setQueryBuilder($this->queryBuilder);
            $opcion->setIdPregunta($id);
            $opcion->guardar();
        }

        return $id;
    }
}
