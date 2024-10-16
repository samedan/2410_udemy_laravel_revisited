<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class PostController extends Controller
{

    // GET View New Post Form
    public function showCreateForm() {
        return view('create-post');
    }


    // POST New Post
    public function storeNewPost(Request $request) {
        $incomingFields = $request->validate([
            'title' => 'required',
            'body' => 'required',
        ]);

        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);

        $incomingFields['user_id'] = auth()->id();
        $newPost = Post::create($incomingFields);
        return redirect("/post/{$newPost->id}")->with('success', 'New post added');
    }

    //GET Single Post
    public function viewSinglePost(Post $post) {
        $ourHtml =strip_tags(Str::markdown($post->body),
            '<p><ul><li><strong><bold><em><h3><h2><h1>' 
    );
        $post['body'] = $ourHtml;
        return view('single-post', [
            'post' => $post
        ]);
    }
}
