<?php

declare(strict_types=1);

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ApiClientSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'public_id'            => 'client_test_001',
            'name'                 => 'Cliente de Prueba Sandbox',
            'client_secret_hash'   => password_hash('test_secret_change_me', PASSWORD_BCRYPT, ['cost' => 12]),
            'status'               => 'active',
            'allowed_llm_providers' => json_encode(['openai', 'anthropic']),
            'rate_limit_per_minute' => 60,
            'monthly_quota'        => 10000,
            'created_at'           => date('Y-m-d H:i:s'),
            'updated_at'           => date('Y-m-d H:i:s'),
        ];

        $this->db->table('api_clients')->insert($data);
    }
}
