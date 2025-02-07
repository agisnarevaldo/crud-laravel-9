<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * index
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        // get posts
        $posts = Post::latest()->paginate(5);

        // render view with posts
        return view('posts.index', compact('posts'));
    }

    /**
     * create
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        //validate form
        $this->validate(request(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required|min:5',
            'content' => 'required|min:10'
        ]);

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/posts', $image->hashName());

        // create post
        Post::create([
            'image' => $image->hashName(),
            'title' => $request->input('title'),
            'content' => $request->input('content')
        ]);

        // redirect to index
        return redirect() -> route('posts.index')->with('success', 'Post created successfully.');
    }
}
