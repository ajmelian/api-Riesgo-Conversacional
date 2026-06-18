<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateApiLlmSessionsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'client_id' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
                'null'       => false,
            ],
            'session_token_hash' => [
                'type'       => 'CHAR',
                'constraint' => 64,
                'null'       => false,
            ],
            'llm_provider' => [
                'type'       => 'VARCHAR',
                'constraint' => 32,
                'null'       => false,
            ],
            'llm_token_ciphertext' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'llm_token_nonce' => [
                'type'       => 'VARCHAR',
                'constraint' => 128,
                'null'       => false,
            ],
            'llm_token_fingerprint' => [
                'type'       => 'CHAR',
                'constraint' => 64,
                'null'       => false,
            ],
            'ip_address' => [
                'type'       => 'VARBINARY',
                'constraint' => 16,
                'null'       => false,
            ],
            'user_agent_hash' => [
                'type'       => 'CHAR',
                'constraint' => 64,
                'null'       => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'last_activity_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'expires_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'revoked_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addUniqueKey('session_token_hash');
        $this->forge->addKey('client_id');
        $this->forge->addKey('expires_at');
        $this->forge->addKey('revoked_at');
        $this->forge->addForeignKey('client_id', 'api_clients', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('api_llm_sessions');
    }

    public function down(): void
    {
        $this->forge->dropTable('api_llm_sessions');
    }
}
