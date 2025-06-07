<?php

namespace PAW\src\App\Modelos;

use PAW\src\Core\Modelo;
use PAW\src\App\Modelos\Evaluacion;

class ColeccionEvaluacion extends Modelo
{
    public $tableEvaluacion = 'evaluaciones';
    public $tablePreguntas = 'preguntas';
    public $tableOpciones = 'opciones';


    public function get($id)
    {
        $evaluacion = new Evaluacion;
        $evaluacion->setQueryBuilder($this->queryBuilder);
        $evaluacion->load($id);
        return $evaluacion;
    }

    public function crear(array $datos): Evaluacion
    {
        $evaluacion = new Evaluacion();
        $evaluacion->setQueryBuilder($this->queryBuilder);
        $evaluacion->set($datos);
        $evaluacion->guardar();
        return $evaluacion;
    }

    public function crearEvaluacion(array $datos): ?int
    {
        return $this->queryBuilder->insertConReturnId($this->tableEvaluacion, $datos);
    }

    public function crearPregunta(array $datos): ?int
    {
        return $this->queryBuilder->insertConReturnId($this->tablePreguntas, $datos);
    }

    public function crearOpcion(array $datos): bool
    {
        return $this->queryBuilder->insertConReturnId($this->tableOpciones, $datos);
    }

    public function buscarPorId(string $id): ?array
    {
        if (empty($id)) {
            return null;
        }

        $resultados = $this->queryBuilder->select('cursos', ['id' => $id]);
        return $resultados[0] ?? null;
    }

    public function obtenerEvaluacionPorCurso(string $idCurso): ?array
    {
        $curso = $this->buscarPorId($idCurso);

        if (!$curso || !isset($curso['id'])) {
            return null;
        }

        $evaluaciones = $this->queryBuilder->select('evaluaciones', ['curso_id' => $curso['id']]);
        return $evaluaciones[0] ?? null;
    }

    public function obtenerEvaluacionConPreguntasPorCurso(string $idCurso): ?array
    {
        $curso = $this->buscarPorId($idCurso);
        if (!$curso || !isset($curso['id'])) {
            return null;
        }

        $evaluaciones = $this->queryBuilder->select('evaluaciones', ['id_curso' => $curso['id']]);
        $evaluacion = $evaluaciones[0] ?? null;

        if (!$evaluacion) {
            return null;
        }

        $preguntas = $this->queryBuilder->select('preguntas', ['id_evaluacion' => $evaluacion['id']]);

        foreach ($preguntas as &$pregunta) {
            $opciones = $this->queryBuilder->select('opciones', ['id_pregunta' => $pregunta['id']]);

            // Detectar cuál es la opción correcta
            foreach ($opciones as $opcion) {
                if ($opcion['es_correcta']) {
                    $pregunta['respuesta_correcta'] = $opcion['texto'];
                    break;
                }
            }

            $pregunta['opciones'] = $opciones;
        }

        $evaluacion['preguntas'] = $preguntas;
        return $evaluacion;
    }

}