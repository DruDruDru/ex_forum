<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CommentRequest;
use App\Services\CommentService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CommentController
{
    protected CommentService $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function store(CommentRequest $request, $postId)
    {
        if ($comment = $this->commentService->create($request, $postId)) {
            return response()->json([
                'data' => $comment
            ], Response::HTTP_CREATED);
        }
        return response()->json([
            'message' => 'Пост не найден'
        ], Response::HTTP_NOT_FOUND);
    }

    public function update(CommentRequest $request, $commentId)
    {
        if ($comment = $this->commentService->update($request, $commentId)) {
            return response()->json([
                'data' => $comment
            ]);
        }
        return response()->json([
            'message' => 'Комментарий не найден'
        ], Response::HTTP_NOT_FOUND);
    }

    public function delete(Request $request, $commentId)
    {
        if ($comment = $this->commentService->delete($commentId)) {
            return response()->json([
                'data' => $comment
            ]);
        }

        return response()->json([
            'message' => 'Комментарий не найден'
        ], Response::HTTP_NOT_FOUND);
    }
}
