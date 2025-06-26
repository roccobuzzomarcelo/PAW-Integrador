<?php

namespace PAW\src\App\Modelos;

use PAW\src\Core\Modelo;
use PAW\src\App\Modelos\Inscripcion;

class ColeccionInscripcion extends Modelo
{
    public $table = 'inscripciones';

    public function contarInscriptosPorCurso(int $cursoId): int
    {
        $resultados = $this->queryBuilder->select($this->table, ['curso_id' => $cursoId]);
        return count($resultados);
    }

    public function obtenerInscriptosPorCurso(int $cursoId): array
    {
        return $this->queryBuilder->select($this->table, ['curso_id' => $cursoId]);
    }

    public function obtenerTodos(): array
    {
        return $this->queryBuilder->select($this->table);
    }

    public function eliminarPorUsuarioYCurso(int $usuarioId, int $cursoId): bool
    {
        return $this->queryBuilder->delete($this->table, [
            'usuario_id' => $usuarioId,
            'curso_id' => $cursoId
        ]);
    }

    public function existeInscripcion($usuarioId, $cursoId): bool
    {
        $resultados = $this->queryBuilder->select('inscripciones', [
            'usuario_id' => $usuarioId,
            'curso_id' => $cursoId
        ]);

        return !empty($resultados);
    }

    public function crearInscripcion(array $datos): bool
    {
        return $this->queryBuilder->insert('inscripciones', $datos);
    }

    public function obtenerNombreInscriptosPorCurso($cursoId): array
    {
        $sql = "
            SELECT i.*, u.nombre 
            FROM inscripciones i
            JOIN usuarios u ON i.usuario_id = u.id
            WHERE i.curso_id = :curso_id
        ";

        $parametros = [ 'curso_id' => $cursoId ];

        return $this->queryBuilder->selectRaw($sql, $parametros);
    }



}
