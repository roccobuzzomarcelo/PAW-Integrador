<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class TablaUsuario extends AbstractMigration
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
        $table = $this->table('usuarios');
        $table
            ->addColumn('correo', 'string', ['limit' => 255])
            ->addColumn('password', 'string', ['limit' => 255])
            ->addColumn('nombre', 'string', ['limit' => 100])
            ->addColumn('tipo_usuario', 'string', ['limit' => 20, 'default' => 'normal'])
            ->addColumn('fecha_creacion', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addIndex(['correo'], ['unique' => true])
            ->create();
    }
}
