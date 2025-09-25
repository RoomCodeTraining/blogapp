<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::query()
            ->with(['categories:id,name,slug', 'user:id,name,email'])
            ->latest('published_at')
            ->paginate(10);

        return response()->json($posts);
    }

    public function show(string $slug)
    {
        $post = Post::query()
            ->with(['categories:id,name,slug', 'user:id,name,email'])
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json($post);
    }
}
