<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = [
            [
                "user_id" => 7,
                "image" => null,
                "content" => 'She belieaved she could, so he did'
            ],
            [
                "user_id" => 10,
                "image" => null,
                "content" => 'Stay soft, but take nosense'
            ],
            [
                "user_id" => 8,
                "image" => null,
                "content" => 'When i doubt, google it!'
            ],
        ];

        foreach ($posts as $postData) {
            Post::create($postData);
        }
    }
}
