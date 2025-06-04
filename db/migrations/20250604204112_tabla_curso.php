<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class TablaCurso extends AbstractMigration
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
        $table = $this->table('cursos');
        $table
            ->addColumn('titulo', 'string')
            ->addColumn('descripcion', 'text')
            ->addColumn('creado_por', 'integer') // FK a usuarios (admin)
            ->addColumn('fecha_creacion', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('activo', 'boolean', ['default' => true])
            ->addColumn('nivel', 'string', ['default' => 'basico'])
            ->addColumn('duracion', 'integer', ['default' => 0]) // Duración en horas
            ->addColumn('imagen', 'string', ['null' => true]) // URL de la imagen del curso
            ->addForeignKey('creado_por', 'usuarios', 'id', ['delete'=> 'CASCADE'])
            ->create();
    }
}
