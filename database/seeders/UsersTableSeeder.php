<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Невідомий автор',
                'email' => 'author_unknown@g.g',
                'password' => bcrypt(Str::random(16)),
            ],
            [
                'name' => 'Автор',
                'email' => 'author1@g.g',
                'password' => bcrypt('123123'),
            ],
        ];

        DB::table('users')->insert($data);
    }
}
