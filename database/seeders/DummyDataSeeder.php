<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        Category::truncate();
        Tag::truncate();
        Post::truncate();
        Comment::truncate();

        DB::table('posts')->insert([
            'title' => 'Test 1',
            'body' => 'Das ist ein Test',
            'user_id' => 100,
            'category_id' => rand(1, 10),
            'is_published' => rand(0, 1),
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),

        ]);

        Category::factory()->times(10)->create();
        Tag::factory()->times(10)->create();
        User::factory()->times(9)->create();
        Post::factory()->times(25)->create();
        Comment::factory()->times(40)->create();

        $data = [];
        for ($i = 0; $i < 60; $i++) {
            $data[] = [
                'post_id' => rand(1, 25),
                'tag_id' => rand(1, 10),
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ];
        }

        DB::table('post_tag')->insert($data);
    }
}
