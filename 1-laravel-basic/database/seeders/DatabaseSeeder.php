<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tasks')->truncate(); # optionnel
        Task::factory(10)->create();

        DB::table('users')->truncate(); # optionnel
        User::factory(10)->create();
    }
}
