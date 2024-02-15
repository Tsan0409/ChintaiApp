<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

// ユーザーデータを挿入
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['name' => 'MasterUser', 'email' => 'test@test.com', 'password' => '$2y$10$rWkcnxJW2DVMZ2m5Xa8wWuODWjA6Vg4ySG22th7nrX86apHou0xFK'],
        ];

        foreach ($users as $user) {
            User::create($user);
        };
    }
}