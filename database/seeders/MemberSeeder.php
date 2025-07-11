<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\User;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil user pertama (akun keluarga)
        $user = User::first();

        if (!$user) {
            $this->command->info('Belum ada user. Harap register dulu.');
            return;
        }

        // Buat 3 member: Suami, Istri, Anak
        $members = [
            ['name' => 'Yosua', 'role' => 'suami'],
            ['name' => 'Juli',  'role' => 'istri'],
            ['name' => 'Anak',  'role' => 'anak'],
        ];

        foreach ($members as $data) {
            Member::firstOrCreate([
                'user_id' => $user->id,
                'name'    => $data['name'],
                'role'    => $data['role'],
            ]);
        }

        $this->command->info('Berhasil menambahkan 3 member.');
    }
}

