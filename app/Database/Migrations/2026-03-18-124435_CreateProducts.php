<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProducts extends Migration
{
    public function up()
    {
    $this->forge->addField([
        'id' => [
            'type' => 'INT',
            'constraint' => 11,
            'unsigned' => true,
            'auto_increment' => true,
        ],
        'sku' => [
            'type' => 'VARCHAR',
            'constraint' => 100,
        ],
        'name' => [
            'type' => 'VARCHAR',
            'constraint' => 255,
        ],
        'stock_quantity' => [
            'type' => 'INT',
            'default' => 0,
        ],
        'price_net' => [
            'type' => 'DECIMAL',
            'constraint' => '10,2',
        ],
        'is_active' => [
            'type' => 'BOOLEAN',
            'default' => true,
        ],
    ]);

    $this->forge->addKey('id', true);
    $this->forge->createTable('products');
    }

    public function down()
    {
        //
    }
}
