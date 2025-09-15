<?php

namespace App\Services;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Post;

class CommentService
{
    protected $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function create(CommentRequest $request, $postId): mixed
    {
        if (Post::posses($postId)) {
            return $this->comment::create([
                'content' => $request->input('content'),
                'user_id' => $request->user()->id,
                'post_id' => (int) $postId
            ]);
        }
        return false;
    }

    public function update(CommentRequest $request, $commentId): mixed
    {
        if (Comment::posses($commentId)) {
            $comment = Comment::find($commentId);
            $comment->update([
                'content' => $request->input('content')
            ]);
            return $comment;
        }
        return false;
    }

    public function delete($commentId)
    {
        if (Comment::posses($commentId)) {
            $comment = Comment::find($commentId);
            $comment->delete();
            return $comment;
        }
        return false;
    }
}
