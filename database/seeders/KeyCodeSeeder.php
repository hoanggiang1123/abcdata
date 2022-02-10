<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KeyCode;

class KeyCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        KeyCode::create([
            'name' => 'password',
            'code' => '123456789'
        ]);
    }
}
