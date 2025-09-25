<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categorie;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;

class CategoryAndPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure one author exists
        $author = User::query()->first() ?? User::factory()->create([
            'name' => 'Author',
            'email' => 'author@example.com',
        ]);

        // Create 5 real categories
        $categoryNames = [
            'Technologie',
            'Tutoriels',
            'Actualités',
            'Avis & Tests',
            'Bonnes pratiques',
        ];

        $categories = collect($categoryNames)->map(function (string $name) {
            return Categorie::query()->firstOrCreate([
                'slug' => Str::slug($name),
            ], [
                'name' => $name,
                'description' => 'Catégorie: '.$name,
            ]);
        });

        // Create 10 posts and attach 1-3 categories each
        for ($i = 1; $i <= 10; $i++) {
            $title = "Article $i";
            $post = Post::query()->create([
                'title' => $title,
                'slug' => Str::slug($title.'-'.Str::random(4)),
                'sub_title' => 'Sous-titre '.$i,
                'body' => str_repeat('Contenu exemple. ', 30),
                'status' => 'published',
                'published_at' => now(),
                'cover_photo_path' => null,
                'photo_alt_text' => null,
                'user_id' => $author->id,
            ]);

            $post->categories()->sync(
                $categories->random(rand(1, 3))->pluck('id')->all()
            );
        }
    }
}
