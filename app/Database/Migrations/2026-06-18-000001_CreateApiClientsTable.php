<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateApiClientsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'public_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
                'null'       => false,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 190,
                'null'       => false,
            ],
            'client_secret_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['active', 'disabled', 'suspended'],
                'default'    => 'active',
                'null'       => false,
            ],
            'allowed_llm_providers' => [
                'type' => 'JSON',
                'null' => false,
            ],
            'rate_limit_per_minute' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'default'    => 60,
                'null'       => false,
            ],
            'monthly_quota' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'default'    => 10000,
                'null'       => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('public_id');
        $this->forge->addKey('status');
        $this->forge->createTable('api_clients');
    }

    public function down(): void
    {
        $this->forge->dropTable('api_clients');
    }
}
