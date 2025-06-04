<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class TablaInscripciones extends AbstractMigration
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
        $table = $this->table('inscripciones');
        $table
            ->addColumn('usuario_id', 'integer')
            ->addColumn('curso_id', 'integer')
            ->addColumn('fecha_inscripcion', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addForeignKey('usuario_id', 'usuarios', 'id')
            ->addForeignKey('curso_id', 'cursos', 'id')
            ->create();
    }
}
