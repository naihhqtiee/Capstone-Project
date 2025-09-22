<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRegistrations extends Migration
{
  public function up()
{
    $this->forge->addField([
        'id' => [
            'type'           => 'INT',
            'auto_increment' => true
        ],
        'first_name' => ['type' => 'VARCHAR', 'constraint' => 100],
        'mi'         => ['type' => 'VARCHAR', 'constraint' => 10],
        'last_name'  => ['type' => 'VARCHAR', 'constraint' => 100],
        'email'      => ['type' => 'VARCHAR', 'constraint' => 100],
        'contact'    => ['type' => 'VARCHAR', 'constraint' => 15],
        'department' => ['type' => 'VARCHAR', 'constraint' => 100],
        'course'     => ['type' => 'VARCHAR', 'constraint' => 100],
        'year'       => ['type' => 'VARCHAR', 'constraint' => 50],
    ]);
    $this->forge->addKey('id', true);
    $this->forge->createTable('registrations');
}


    public function down()
    {
        //
    }
}
