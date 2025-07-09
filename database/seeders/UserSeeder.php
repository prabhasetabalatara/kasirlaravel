<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ganti dari ->first() menjadi ->firstOrCreate()
        // Ini akan mencari Role 'Admin', atau membuatnya jika belum ada.
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $cashierRole = Role::firstOrCreate(['name' => 'Cashier']);

        // Buat user Admin
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id, // Sekarang $adminRole dijamin tidak akan null
            ]
        );

        // Buat user Kasir
        User::firstOrCreate(
            ['email' => 'cashier@example.com'],
            [
                'name' => 'Cashier User',
                'password' => Hash::make('password'),
                'role_id' => $cashierRole->id, // $cashierRole juga dijamin tidak akan null
            ]
        );
    }
}