<?php

namespace Database\Seeders;

use App\Models\Building;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $table = 'comments';
        $json = file_get_contents('./database/json/comments.json');
        $comments = json_decode($json);
        foreach ($comments as $comment) {
            $data = (array) $comment;
            $data['created_at'] = now()->format('Y-m-d H:i:s');
            DB::table($table)->insert($data);
        }
    }
}
