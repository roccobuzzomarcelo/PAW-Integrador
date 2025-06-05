<?php

namespace PAW\src\App\Modelos;

use PAW\src\Core\Modelo;
use PAW\src\App\Modelos\Curso;

class ColeccionCursos extends Modelo
{
    public $table = 'cursos';

    public function get($id){
        $curso = new Curso;
        $curso->setQueryBuilder($this->queryBuilder);
        $curso->load($id);
        return $curso;
    }

    public function getAll(){
        $cursos = $this->queryBuilder->select($this->table);
        $coleccionCursos = [];
        foreach ($cursos as $curso){
            $nuevoCurso = new Curso;
            $nuevoCurso->set($curso);
            $coleccionCursos[] = $nuevoCurso;
        }
        return $coleccionCursos;
    }

    public function crear($datos){
        return $this->queryBuilder->insertConReturnId($this->table, $datos);
    }

    public function guardarTema($tema){
        return $this->queryBuilder->insert("temas", $tema);
    }

    public function guardarModulos(array $datos){
        return $this->queryBuilder->insert("modulos", $datos);
    }

    public function getModulosCurso($idCurso){
        return $this->queryBuilder->select("modulos", ["id_curso"=>$idCurso]);
    }

    public function getModulo($idCurso, $idModulo){
        return $this->queryBuilder->select("modulos", ["id_curso" => $idCurso, "id" => $idModulo]);
    }

    public function getEvaluacion($idCurso){
        return $this->queryBuilder->select("evaluaciones", ["id_curso" => $idCurso]);
    }
}