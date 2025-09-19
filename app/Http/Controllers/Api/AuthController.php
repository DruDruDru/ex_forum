<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\PasswordResetSendCodeRequest;
use App\Http\Requests\PasswordResetVerifyRequest;
use App\Http\Requests\SendCodeAgainRequest;
use App\Http\Requests\VerifyCodeRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(AuthRequest $request)
    {
        if (!Auth::guard('web')->attempt($request->only(['email', 'password']))) {
            return response()->json([
                'message' => 'Неверный пароль и/или электронная почта'
            ], Response::HTTP_UNAUTHORIZED);
        }
        return response()->json([
            'data' => [
                'bearer_token' => $this->authService->createJWT($request)
            ]
        ]);
    }

    public function verifyCode(VerifyCodeRequest $request)
    {
        if ($this->authService->verifyCode($request)) {
            return response()->json([
                'message' => 'Вы подтвердили учётную запись'
            ]);
        }
        return response()->json([
            'message' => 'Неверный код подтверждения'
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function sendCodeAgain(SendCodeAgainRequest $request)
    {
        $this->authService->sendCodeAgain($request);
        return response()->json([
            'message' => 'На вашу почту отправлен код подтверждения'
        ]);
    }

    public function passwordResetSendCode(PasswordResetSendCodeRequest $request)
    {
        $this->authService->passwordResetSendCode($request);
        return response()->json([
            'message' => 'На почту отправлен код для сброса пароля'
        ]);
    }

    public function passwordResetVerify(PasswordResetVerifyRequest $request)
    {
        if ($this->authService->passwordResetVerify($request)) {
            return response()->json([
                'message' => 'Пароль изменён'
            ]);
        }
        return response()->json([
            'message' => 'Неверный код подтверждения'
        ], Response::HTTP_BAD_REQUEST);
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request);
        return response()->json([
            'message' => 'Успешный выход'
        ]);
    }
}
