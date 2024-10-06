<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'auto_increment' => true],
            'username'      => ['type' => 'VARCHAR', 'constraint' => 100],
            'email'         => ['type' => 'VARCHAR', 'constraint' => 100],
            'password'      => ['type' => 'VARCHAR', 'constraint' => 255],
            'department_id' => ['type' => 'INT', 'null' => true], // Foreign key field
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);

        // Add primary key
        $this->forge->addKey('id', true);

        // Add foreign key to 'department_id' referencing 'department.id'
        $this->forge->addForeignKey('department_id', 'department', 'id', 'SET NULL', 'CASCADE');

        // Create 'users' table
        $this->forge->createTable('users');
    }

    public function down()
    {
        // Drop the 'users' table and foreign key
        $this->forge->dropTable('users');
    }
}
