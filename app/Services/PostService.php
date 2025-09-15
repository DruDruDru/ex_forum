<?php

namespace App\Services;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

    public function get($id)
    {
        return preg_match('/^[0-9]*$/', $id) ?
            $this->post::find($id):
            null;
    }

    public function create(PostRequest $request)
    {
        if ($image = $request->file('image')) {
            $filename = uuid_create().'_'.$image->getClientOriginalName();

            $path = $image->storeAs("posts", $filename, 'public');
        }

        return $this->post::create([
            ...$request->all(),
            'user_id' => Auth::id(),
            'image_path' => isset($path) ? Storage::url($path) : null
        ]);
    }
}
