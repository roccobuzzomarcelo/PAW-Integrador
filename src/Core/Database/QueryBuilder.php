<?php

namespace PAW\src\Core\Database;

use PAW\src\Core\Traits\Loggable;
use PDO;

class QueryBuilder
{
    use Loggable;
    private PDO $pdo;
    
    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

    public function select($tabla, $parametros = []){
        $condiciones = [];
        $binds = [];

        if (isset($parametros['consulta']) && $tabla == 'usuarios') {
            $condiciones[] = "(nombre LIKE :nombre OR email = :email)";
            $binds[':nombre'] = '%' . $parametros['consulta'] . '%';
            $binds[':email'] = $parametros['consulta'];
        }

        // Filtro por IDs
        if (isset($parametros['ids']) && is_array($parametros['ids']) && count($parametros['ids']) > 0) {
            $placeholders = [];
            foreach ($parametros['ids'] as $i => $id) {
                $ph = ":id_$i";
                $placeholders[] = $ph;
                $binds[$ph] = $id;
            }
            $condiciones[] = "id IN (" . implode(",", $placeholders) . ")";
        }

        // Otras condiciones libres si las hay
        if (isset($parametros['condiciones']) && is_array($parametros['condiciones'])) {
            foreach ($parametros['condiciones'] as $cond) {
                $condiciones[] = $cond;
            }
        }
        if (isset($parametros['binds']) && is_array($parametros['binds'])) {
            foreach ($parametros['binds'] as $clave => $valor) {
                $binds[$clave] = $valor;
            }
        }

        $where = count($condiciones) > 0 ? implode(" AND ", $condiciones) : "1 = 1";

        $query = "SELECT * FROM {$tabla} WHERE {$where}";

        // Paginación
        if (isset($parametros['limit']) && is_numeric($parametros['limit']) && $parametros['limit'] > 0) {
            $query .= " LIMIT :limit";
            $binds[':limit'] = (int)$parametros['limit'];
        }

        if (isset($parametros['offset']) && is_numeric($parametros['offset']) && $parametros['offset'] >= 0) {
            $query .= " OFFSET :offset";
            $binds[':offset'] = (int)$parametros['offset'];
        }
        $sentencia = $this->pdo->prepare($query);

        foreach ($binds as $clave => $valor) {
            $tipo = is_int($valor) ? \PDO::PARAM_INT : \PDO::PARAM_STR;
            $sentencia->bindValue($clave, $valor, $tipo);
        }

        $sentencia->setFetchMode(\PDO::FETCH_ASSOC);
        $sentencia->execute();
        return $sentencia->fetchAll();
    }

    public function insert($tabla, $valores){
        $campos = array_keys($valores);
        $placeholders = array_map(function($campo) {
            return ":$campo";
        }, $campos);

        $query = "INSERT INTO {$tabla} (" . implode(", ", $campos) . ") VALUES (" . implode(", ", $placeholders) . ")";
        $stmt = $this->pdo->prepare($query);

        foreach ($valores as $campo => $valor) {
            $tipo = is_int($valor) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue(":$campo", $valor, $tipo);
        }
        return $stmt->execute();
    }

    public function update($tabla, $valores, $id){
        $campos = array_keys($valores);
        $set = [];
        foreach ($campos as $campo) {
            $set[] = "$campo = :$campo";
        }

        $query = "UPDATE {$tabla} SET " . implode(", ", $set) . " WHERE id = :id";
        $stmt = $this->pdo->prepare($query);

        foreach ($valores as $campo => $valor) {
            $tipo = is_int($valor) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue(":$campo", $valor, $tipo);
        }
        $stmt->bindValue(":id", (int)$id, PDO::PARAM_INT);

        return $stmt->execute();
        

    }

    public function delete(){
    
    }
    public function count(string $tabla, array $parametros = []): int
    {
        $condiciones = [];
        $binds = [];

        // Filtro por búsqueda textual
        if (isset($parametros['consulta'])) {
            $condiciones[] = "(titulo LIKE :consulta OR autor LIKE :consulta)";
            $binds[':consulta'] = '%' . $parametros['consulta'] . '%';
        }

        // Filtro por IDs
        if (isset($parametros['ids']) && is_array($parametros['ids']) && count($parametros['ids']) > 0) {
            $placeholders = [];
            foreach ($parametros['ids'] as $i => $id) {
                $ph = ":id_$i";
                $placeholders[] = $ph;
                $binds[$ph] = $id;
            }
            $condiciones[] = "id IN (" . implode(",", $placeholders) . ")";
        }

        // Otras condiciones personalizadas
        if (isset($parametros['condiciones']) && is_array($parametros['condiciones'])) {
            foreach ($parametros['condiciones'] as $cond) {
                $condiciones[] = $cond;
            }
        }

        $where = count($condiciones) > 0 ? " WHERE " . implode(" AND ", $condiciones) : "";

        $query = "SELECT COUNT(*) as total FROM {$tabla}{$where}";
        $stmt = $this->pdo->prepare($query);

        foreach ($binds as $clave => $valor) {
            $tipo = is_int($valor) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($clave, $valor, $tipo);
        }

        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)$resultado['total'];
    }
}