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
}
