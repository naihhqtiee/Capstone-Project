<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAnonymousComplaintsTable extends Migration
{
public function up()
{
    $this->forge->addField([
        'id' => [
            'type' => 'INT',
            'auto_increment' => true,
            'unsigned' => true,
        ],
        'date' => ['type' => 'DATE'],
        'location' => ['type' => 'VARCHAR', 'constraint' => 255],
        'description' => ['type' => 'TEXT'],
        'files' => ['type' => 'TEXT', 'null' => true], // store as JSON
        'resolution' => ['type' => 'VARCHAR', 'constraint' => 100],
        'resolution_other' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        'type' => ['type' => 'VARCHAR', 'constraint' => 100],
        'impact' => ['type' => 'TEXT', 'null' => true],
        'status' => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'pending'],
    ]);

    $this->forge->addKey('id', true);
    $this->forge->createTable('anonymous_complaints');
}


    public function down()
    {
        //
    }
}
