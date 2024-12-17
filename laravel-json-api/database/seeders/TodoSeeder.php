<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Todo;

class TodoSeeder extends Seeder
{
    public function run()
    {
        Todo::create(['name' => 'Sample Todo 1', 'isCompleted' => false]);
        Todo::create(['name' => 'Sample Todo 2', 'isCompleted' => true]);
    }
}
