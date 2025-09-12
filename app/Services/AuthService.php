<?php

namespace App\Services;

use App\Http\Requests\AuthRequest;
use App\Models\User;

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
}
