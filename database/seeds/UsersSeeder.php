<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if( ! User::where('email', 'usuario@meudominio.com.br')->exists() )
        {
            User::create([
                'name' => 'Usuario',
                'email' => 'usuario@meudominio.com.br',
                'password' => bcrypt(123),
            ]);
        }
    }
}
