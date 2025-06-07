<?php

namespace PAW\src\App\Modelos;

use PAW\src\Core\Modelo;
use PAW\src\App\Modelos\Curso;

class ColeccionCursos extends Modelo
{
    public $table = 'cursos';

    public function get($id)
    {
        $curso = new Curso;
        $curso->setQueryBuilder($this->queryBuilder);
        $curso->load($id);
        return $curso;
    }

    public function getAll()
    {
        $cursos = $this->queryBuilder->select($this->table);
        $coleccionCursos = [];
        foreach ($cursos as $curso) {
            $nuevoCurso = new Curso;
            $nuevoCurso->set($curso);
            $coleccionCursos[] = $nuevoCurso;
        }
        return $coleccionCursos;
    }

    public function crear($datos)
    {
        return $this->queryBuilder->insertConReturnId($this->table, $datos);
    }

    public function guardarTema($tema)
    {
        return $this->queryBuilder->insert("temas", $tema);
    }

    public function guardarModulos(array $datos)
    {
        return $this->queryBuilder->insert("modulos", $datos);
    }

    public function getModulosCurso($idCurso)
    {
        return $this->queryBuilder->select("modulos", ["curso_id" => $idCurso]);
    }

    public function getTemasCurso($idCurso)
    {
        return $this->queryBuilder->select("temas", ["curso_id" => $idCurso]);
    }

    public function getModulo($idModulo)
    {
        return current($this->queryBuilder->select("modulos", ["id" => $idModulo]));
    }

    public function getEvaluacion($idCurso)
    {
        return $this->queryBuilder->select("evaluaciones", ["curso_id" => $idCurso]);
    }

    public function marcarCompletado($moduloId, $cursoId, $usuarioId)
    {
        $datosProgreso = [
            "completado" => true,
            "fecha_completado" => date("Y-m-d H:i:s")
        ];
        return $this->queryBuilder->update("progresos", $datosProgreso, ["modulo_id" => $moduloId, "curso_id" => $cursoId, "usuario_id" => $usuarioId]);
    }

    public function existeProgreso($usuarioId, $cursoId, $moduloId)
    {
        $datos = $this->queryBuilder->select("progresos", ["curso_id" => $cursoId, "usuario_id" => $usuarioId, "modulo_id" => $moduloId]);
        return !empty($datos);
    }

    public function estaCompletado($usuarioId, $cursoId, $moduloId)
    {
        $datos = current($this->queryBuilder->select("progresos", ["curso_id" => $cursoId, "usuario_id" => $usuarioId, "modulo_id" => $moduloId]));
        return $datos["completado"];
    }

    public function crearProgreso($usuarioId, $cursoId, $moduloId)
    {
        $datosProgreso = [
            "usuario_id" => $usuarioId,
            "curso_id" => $cursoId,
            "modulo_id" => $moduloId,
            "completado" => false
        ];
        $this->queryBuilder->insert("progresos", $datosProgreso);
    }
}