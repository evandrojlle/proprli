<?php

namespace Database\Seeders;

use App\Models\Building;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $table = 'tasks';
        $json = file_get_contents('./database/json/tasks.json');
        $tasks = json_decode($json);
        foreach ($tasks as $task) {
            $data = (array) $task;
            $data['created_at'] = now()->format('Y-m-d H:i:s');
            $row = DB::table($table)->select()->where('name', '=', $data['name'])->first();
            if (! $row) {
                DB::table($table)->insert($data);
            }
        }
    }
}
