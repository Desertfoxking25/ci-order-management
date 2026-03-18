<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrderItems extends Migration
{
    public function up()
    {
    $this->forge->addField([
        'id' => [
            'type' => 'INT',
            'unsigned' => true,
            'auto_increment' => true,
        ],
        'order_id' => [
            'type' => 'INT',
            'unsigned' => true,
        ],
        'product_id' => [
            'type' => 'INT',
            'unsigned' => true,
        ],
        'quantity' => [
            'type' => 'INT',
        ],
        'price_net' => [
            'type' => 'DECIMAL',
            'constraint' => '10,2',
        ],
    ]);

    $this->forge->addKey('id', true);
    $this->forge->createTable('order_items');
    }

    public function down()
    {
        //
    }
}
