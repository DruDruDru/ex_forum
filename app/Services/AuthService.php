<?php

namespace App\Services;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\PasswordResetSendCodeRequest;
use App\Http\Requests\PasswordResetVerifyRequest;
use App\Http\Requests\SendCodeAgainRequest;
use App\Http\Requests\VerifyCodeRequest;
use App\Jobs\SendEmailJob;
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

        if ((int)$code === $request->input('code')) {
            $user = $this->user->where('email', $email)->firstOrFail();
            $user->email_verified_at = now();
            $user->save();
            Cache::delete("email_verification:$email");

            return true;
        }
        return false;
    }

    public function sendCodeAgain(SendCodeAgainRequest $request)
    {
        $email = $request->input('email');

        $code = rand(100000, 999999);

        Cache::put("email_verification:$email", $code, 300);

        SendEmailJob::dispatch($email, $code);
    }

    public function passwordResetSendCode(PasswordResetSendCodeRequest $request)
    {
        $email = $request->input('email');

        $code = rand(100000, 999999);

        Cache::put("password_reset:$email", $code, 300);

        SendEmailJob::dispatch($email, $code);
    }

    public function passwordResetVerify(PasswordResetVerifyRequest $request)
    {
        $email = $request->input('email');

        $code = Cache::get("password_reset:$email");

        if ($code === $request->input('code')) {
            $user = $this->user->where('email', $email)->firstOrFail();
            $user->password_reset_at = now();
            $user->password = $request->input('new_password');
            $user->save();

            return true;
        }

        return false;
    }
}
