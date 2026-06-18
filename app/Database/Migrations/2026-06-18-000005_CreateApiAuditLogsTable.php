<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateApiAuditLogsTable extends Migration
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
                'null'       => true,
            ],
            'actor_type' => [
                'type'       => 'ENUM',
                'constraint' => ['api_client', 'admin_user', 'system'],
                'null'       => false,
            ],
            'actor_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 128,
                'null'       => true,
            ],
            'event_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 96,
                'null'       => false,
            ],
            'trace_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
                'null'       => false,
            ],
            'ip_address' => [
                'type'       => 'VARBINARY',
                'constraint' => 16,
                'null'       => true,
            ],
            'user_agent_hash' => [
                'type'       => 'CHAR',
                'constraint' => 64,
                'null'       => true,
            ],
            'metadata_json' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('client_id');
        $this->forge->addKey('event_type');
        $this->forge->addKey('trace_id');
        $this->forge->addKey('created_at');
        $this->forge->addForeignKey('client_id', 'api_clients', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('api_audit_logs');
    }

    public function down(): void
    {
        $this->forge->dropTable('api_audit_logs');
    }
}
