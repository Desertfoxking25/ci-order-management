<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOrderStatusLog extends Migration
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
        'from_status' => [
            'type' => 'VARCHAR',
            'constraint' => 50,
        ],
        'to_status' => [
            'type' => 'VARCHAR',
            'constraint' => 50,
        ],
        'changed_at' => [
            'type' => 'DATETIME',
        ],
    ]);

    $this->forge->addKey('id', true);
    $this->forge->createTable('order_status_log');
    }

    public function down()
    {
        //
    }
}
