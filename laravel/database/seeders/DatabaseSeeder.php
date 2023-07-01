<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Role::create(['name' => 'User']);
        Role::create(['name' => 'Moderator']);
        Role::create(['name' => 'Administrator']);

        User::create([
            'name' => 'Test User',
            'role_id' => 1,
            'email' => 'test.user@gmail.com',
            'password' => 'Abc123000!',
        ]);
        User::create([
            'name' => 'Test Moderator',
            'role_id' => 2,
            'email' => 'test.moderator@gmail.com',
            'password' => 'Abc123000!',
        ]);
        User::create([
            'name' => 'Test Administrator',
            'role_id' => 3,
            'email' => 'test.administrator@gmail.com',
            'password' => 'Abc123000!',
        ]);
    }
}
