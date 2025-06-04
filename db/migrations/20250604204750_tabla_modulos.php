<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class TablaModulos extends AbstractMigration
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
        $table = $this->table('modulos');
        $table
            ->addColumn('curso_id', 'integer') // FK a cursos
            ->addColumn('titulo', 'string')
            ->addColumn('descripcion', 'text', ['null' => true]) // Descripción opcional del módulo
            ->addColumn('tipo', 'string', ['limit' => 20]) // tipo de recurso: video, pdf, enlace, etc.
            ->addColumn('url', 'string') // puede ser enlace o ruta local
            ->addColumn('orden', 'integer')
            ->addForeignKey('curso_id', 'cursos', 'id', ['delete'=> 'CASCADE'])
            ->create();
    }
}
