<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use App\Services\PostService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $tag = $request->get('tag');
        $postService = new PostService($tag);
        $data = $postService->lists();
        $layout = $tag ? Tag::layout($tag) : 'blog.layouts.index';
//        $posts = Post::where('published_at', '<=', Carbon::now())
//                ->orderBy('published_at', 'desc')
//                ->paginate(config('blog.posts_per_page'));

        return view($layout, $data);
    }

    public function showPost($slug, Request $request)
    {
        $post = Post::with('tags')->where('slug', $slug)->firstOrFail();
        $tag = $request->get('tag');
        if ($tag) {
            $tag = Tag::where('tag', $tag)->firstOrFail();
        }
//        $post = Post::where('slug', $slug)->firstOrFail();
        return view($post->layout, compact('post', 'tag'));
    }
}
