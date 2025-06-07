<?php

namespace PAW\src\App\Modelos;

use PAW\src\Core\Exceptions\InvalidValueFormatException;
use PAW\src\Core\Modelo;

class Evaluacion extends Modelo
{
    public $table = 'evaluaciones';

    public $campos = [
        "id" => null,
        "id_curso" => null,
        "titulo" => null,
    ];

    private array $preguntas = [];

    public function agregarPregunta(Pregunta $pregunta)
    {
        $this->preguntas[] = $pregunta;
    }

    public function setId($id)
    {
        if (!is_numeric($id) || intval($id) < 0) {
            throw new InvalidValueFormatException("El ID debe ser un número entero positivo.");
        }
        $this->campos['id'] = intval($id);
    }

    public function setId_curso($id_curso)
    {
        if (!is_numeric($id_curso) || intval($id_curso) <= 0) {
            throw new InvalidValueFormatException("El id_curso debe ser un número entero positivo.");
        }
        $this->campos['id_curso'] = intval($id_curso);
    }

    public function setTitulo(string $titulo)
    {
        $titulo = trim($titulo);
        if ($titulo === '') {
            throw new InvalidValueFormatException("El titulo no puede estar vacío.");
        }
        $this->campos['titulo'] = $titulo;
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
        $parametros = ['id' => $id]; // convertimos a array
        $resultado = $this->queryBuilder->select($this->table, $parametros);
        $record = current($resultado);
        $this->set($record);
    }

    public function guardar()
    {
        // Validación
        foreach (['id_curso', 'titulo'] as $campo) {
            if (empty($this->campos[$campo])) {
                throw new \Exception("El campo '$campo' no puede estar vacío.");
            }
        }

        // Insert evaluación
        $datos = $this->campos;
        unset($datos['id']);
        $id = $this->queryBuilder->insert($this->table, $datos);
        $this->campos['id'] = $id;

        // Guardar preguntas y sus opciones
        foreach ($this->preguntas as $pregunta) {
            $pregunta->setQueryBuilder($this->queryBuilder);
            $pregunta->setIdEvaluacion($id);
            $pregunta->guardar();
        }

        return $id;
    }
}
