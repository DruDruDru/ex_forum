<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GradeRequest;
use App\Services\GradeService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GradeController extends Controller
{
    protected $gradeService;

    public function __construct(GradeService $gradeService)
    {
        $this->gradeService = $gradeService;
    }

    public function store(GradeRequest $request, $postId)
    {
        if ($message = $this->gradeService->create($request, $postId)) {
            return response()->json([
                'message' => $message
            ]);
        }

        return response()->json([
            'message' => 'Пост не найден'
        ], Response::HTTP_NOT_FOUND);
    }
}
