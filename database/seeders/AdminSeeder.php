<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!User::where('email', config('auth.admin.email'))->exists()) {
            $user = new User();
            $user->name = config('auth.admin.name');
            $user->email = config('auth.admin.email');
            $user->password = Hash::make(config('auth.admin.password'));
            $user->save();
        }
    }
}
