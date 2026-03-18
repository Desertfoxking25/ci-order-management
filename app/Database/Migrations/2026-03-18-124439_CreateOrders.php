<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrders extends Migration
{
    public function up()
    {
    $this->forge->addField([
        'id' => [
            'type' => 'INT',
            'unsigned' => true,
            'auto_increment' => true,
        ],
        'customer_name' => [
            'type' => 'VARCHAR',
            'constraint' => 255,
        ],
        'status' => [
            'type' => 'VARCHAR',
            'constraint' => 50,
            'default' => 'draft',
        ],
        'created_at' => [
            'type' => 'DATETIME',
            'null' => true,
        ],
        'updated_at' => [
            'type' => 'DATETIME',
            'null' => true,
        ],
    ]);

    $this->forge->addKey('id', true);
    $this->forge->createTable('orders');
    }

    public function down()
    {
        //
    }
}
