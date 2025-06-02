<?php

namespace PAW\src\Core;

use PAW\src\Core\Database\QueryBuilder;
use PAW\src\Core\Traits\Loggable;

class Modelo{
    use Loggable;
    public QueryBuilder $queryBuilder;

    public function setQueryBuilder(QueryBuilder $queryBuilder){
        $this->queryBuilder = $queryBuilder;
    }
}