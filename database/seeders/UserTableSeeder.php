<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'first_name' => 'Smart',
            'last_name'  => 'Ports',
            'email'      => 'info@smartports.com',
            'password'   => Hash::make('Smart@00'),
        ]);
    }
}
