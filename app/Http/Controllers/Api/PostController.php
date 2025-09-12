<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Services\PostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index(Request $request)
    {
        return response()->json([
            'data' => $this->postService->getAll()
        ]);
    }

    public function store(PostRequest $request)
    {
        return response()->json([
            'data' => $this->postService->create($request)
        ]);
    }
}
