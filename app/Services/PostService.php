<?php

namespace App\Services;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostService
{
    protected $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function getAll()
    {
        return $this->post::all();
    }

    public function create(PostRequest $request)
    {
        return $this->post::create([
            ...$request->all(),
            'user_id' => Auth::id()
        ]);
    }
}
