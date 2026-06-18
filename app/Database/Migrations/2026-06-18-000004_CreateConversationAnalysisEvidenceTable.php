<?php

declare(strict_types=1);

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateConversationAnalysisEvidenceTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'analysis_id' => [
                'type'       => 'BIGINT',
                'unsigned'   => true,
                'null'       => false,
            ],
            'message_external_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 128,
                'null'       => false,
            ],
            'category' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
                'null'       => false,
            ],
            'excerpt' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'reason' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->addKey('analysis_id');
        $this->forge->addKey('category');
        $this->forge->addForeignKey('analysis_id', 'conversation_analyses', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('conversation_analysis_evidence');
    }

    public function down(): void
    {
        $this->forge->dropTable('conversation_analysis_evidence');
    }
}
