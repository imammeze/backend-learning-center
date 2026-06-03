<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleAndAdminSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['superadmin', 'admin', 'guru', 'orang_tua', 'siswa_mandiri'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        $this->command->info('Roles berhasil dibuat: ' . implode(', ', $roles));

        $admin = User::firstOrCreate(
            ['email' => 'admin@batamerah.id'],
            [
                'name' => 'Super Admin',
                'whatsapp_number' => '08112841212',
                'password' => Hash::make('admin@1234'),
            ]
        );

        $admin->assignRole('superadmin');

        $this->command->info('Akun admin berhasil dibuat:');
        $this->command->info('  Email   : admin@batamerah.id');
        $this->command->info('  No WA   : 08112841212');
        $this->command->info('  Password: admin@1234');
    }
}
