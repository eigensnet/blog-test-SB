<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\User;
use Illuminate\Support\Facades\Mail;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'category', 'tags', 'comments'])->paginate(10);

        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = Category::pluck('name', 'id')->all();
        $tags = Tag::pluck('name', 'name')->all();
        $user_id = User::pluck('name', 'id')->all();

        return view('admin.posts.create', compact('categories', 'tags', 'user_id'));
    }

    public function store(PostRequest $request)
    {
        $post = Post::create(
            [
                'title' => $request->title,
                'body' => $request->body,
                'category_id' => $request->category_id,
                'user_id' => $request->user_id,
            ]
        );

        $tagsId = collect($request->tags)->map(
            function ($tag) {
                return Tag::firstOrCreate(['name' => $tag])->id;
            }
        );

        $post->tags()->attach($tagsId);

        $adminEmail = "bani@posteo.de";
        $title = 'Test E-Mail';
        $link = url("/posts/$post->id");
        $content = "Link zum Post: $link";
        try {
            Mail::raw($content, function ($message) use ($adminEmail, $title) {
                $message->from('info@paaliaq-studio.com', 'EmailDemo');
                $message->to($adminEmail)->subject($title);
                // flash()->overlay('Sending Mail successfully.');
            });
        } catch (\Exception $e) {\Log::error('E-Mail konnte nicht gesendet werden: ' . $e->getMessage());
            // flash()->overlay('Sending Mail failed.');
        }

        flash()->overlay('Post 123 created successfully.');

        return redirect('/admin/posts');
    }

    public function show(Post $post)
    {
        $post = $post->load(['user', 'category', 'tags', 'comments']);

        return view('admin.posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        if ($post->user_id != auth()->user()->id && auth()->user()->is_admin == false) {
            flash()->overlay("You can't edit other peoples post.");

            return redirect('/admin/posts');
        }

        $categories = Category::pluck('name', 'id')->all();
        $tags = Tag::pluck('name', 'name')->all();
        $user_id = User::pluck('name', 'id');

        return view('admin.posts.edit', compact('post', 'categories', 'tags', 'user_id'));
    }

    public function update(PostRequest $request, Post $post)
    {
        $post->update(
            [
                'title' => $request->title,
                'body' => $request->body,
                'category_id' => $request->category_id,
                'user_id' => $request->user_id,
            ]
        );

        $tagsId = collect($request->tags)->map(
            function ($tag) {
                return Tag::firstOrCreate(['name' => $tag])->id;
            }
        );

        $post->tags()->sync($tagsId);
        flash()->overlay('Post updated successfully.');

        return redirect('/admin/posts');
    }

    public function destroy(Post $post)
    {
        if ($post->user_id != auth()->user()->id && auth()->user()->is_admin == false) {
            flash()->overlay("You can't delete other peoples post.");

            return redirect('/admin/posts');
        }

        $post->delete();
        flash()->overlay('Post deleted successfully.');

        return redirect('/admin/posts');
    }

    public function publish(Post $post)
    {
        $post->is_published = !$post->is_published;
        $post->save();
        flash()->overlay('Post changed successfully.');

        return redirect('/admin/posts');
    }
}
