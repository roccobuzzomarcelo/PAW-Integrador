<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class TablaTemas extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table('temas');
        $table
            ->addColumn('curso_id', 'integer') // FK a cursos
            ->addColumn('titulo', 'string')    // Ej: "Python", "Algoritmos"
            ->addForeignKey('curso_id', 'cursos', 'id', ['delete' => 'CASCADE'])
            ->create();
    }
}
