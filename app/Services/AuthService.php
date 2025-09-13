<?php

namespace App\Services;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\VerifyCodeRequest;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class AuthService
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function createJWT(AuthRequest $request)
    {
        $token = $this->user->where('email', $request->input('email'))
            ->firstOrFail()
            ->createToken('bearer_token')->plainTextToken;

        return $token;
    }

    public function verifyCode(VerifyCodeRequest $request): bool
    {
        $email = $request->input('email');

        $code = Cache::get("email_verification:$email");

        if ($code === $request->input('code')) {
            $user = $this->user->where('email', $email)->firstOrFail();
            $user->email_verified_at = now();
            $user->save();
            Cache::delete("email_verification:$email");

            return true;
        }
        return false;
    }
}
