<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user1 = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
            ]
        );

        $user2 = User::updateOrCreate(
            ['id' => 2],
            [
                'name' => 'User 2',
                'email' => 'user2@example.com',
                'password' => Hash::make('password'),
            ]
        );

        $user3 = User::updateOrCreate(
            ['id' => 3],
            [
                'name' => 'User 3',
                'email' => 'user3@example.com',
                'password' => Hash::make('password'),
            ]
        );

        $room = Room::firstOrCreate(
            ['name' => 'General Chat'],
            ['type' => 'group']
        );

        $room->members()->syncWithoutDetaching([$user2->id, $user3->id]);

        // Keep sequence in sync after explicit IDs so future inserts don't collide.
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("SELECT setval(pg_get_serial_sequence('users', 'id'), (SELECT COALESCE(MAX(id), 1) FROM users))");
        }
    }
}
