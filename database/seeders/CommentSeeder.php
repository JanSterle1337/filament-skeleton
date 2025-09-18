<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $posts = Post::all();

        foreach ($posts as $post) {
            $topComments = Comment::factory(5)->create([
                'post_id' => $post->id,
                'user_id' => $users->random()->id,
                'parent_id' => null,
                'created_at' => Carbon::today()->subDays(rand(0, 365)),
            ]);


            foreach ($topComments as $comment) {
                Comment::factory(rand(2,3))->create([
                    'post_id' => $post->id,
                    'user_id' => $users->random()->id,
                    'parent_id' => $comment->id,
                    'created_at' => Carbon::today()->subDays(rand(0, 365)),
                ]);
            }
        }
    }
}
