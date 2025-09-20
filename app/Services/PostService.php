<?php

namespace App\Services;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostService
{
    protected $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function getAll(Request $request)
    {
        return match ($request->input('only')) {
            'subscriptions' => $this->post->subscriptions()->get(),
            'liked' => $this->post->liked()->get(),
            default => $this->post->all(),
        };
    }

    public function get($id)
    {
        return preg_match('/^[0-9]*$/', $id) ?
            $this->post::find($id):
            null;
    }

    public function create(PostRequest $request)
    {
        if ($file = $request->file('file')) {
            $filename = uuid_create().'_'.$file->getClientOriginalName();

            $path = $file->storeAs("posts", $filename, 'public');
        }

        return $this->post::create([
            ...$request->all(),
            'user_id' => Auth::id(),
            'file_path' => isset($path) ? Storage::url($path) : null
        ]);
    }
}
