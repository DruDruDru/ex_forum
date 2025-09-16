<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionController extends Controller
{
    protected $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    public function store(Request $request, $creatorId)
    {
        if ($message = $this->subscriptionService->create($creatorId)) {
            return response()->json([
                'message' => $message
            ], Response::HTTP_CREATED);
        }
        return response()->json([
            'message' => 'Прозошла ошибка при подписке'
        ], Response::HTTP_BAD_REQUEST);
    }
}
