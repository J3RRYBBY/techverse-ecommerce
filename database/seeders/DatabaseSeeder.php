<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::firstOrCreate(
            ['email' => env('SUPERADMIN_EMAIL')],
            [
                'name' => env('SUPERADMIN_NAME'),
                'password' => Hash::make(env('SUPERADMIN_PASSWORD')),
                'role' => 'superadmin',
            ],
        );

        // User::updateOrCreate(
        //     ['email' => 'superadmin@techverse.com'],
        //     [
        //         'name' => 'Super Admin',
        //         'password' => Hash::make('12345678'),
        //         'role' => 'superadmin',
        //     ],
        // );
    }
}
