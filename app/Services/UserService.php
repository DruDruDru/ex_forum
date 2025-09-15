<?php

namespace App\Services;

use App\Jobs\SendEmailJob;
use App\Mail\EmailVerificationMail;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
class UserService
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function registerUser($request): void
    {
        $this->user::create($request->all());

        $email = $request->input('email');

        $code = rand(100000, 999999);

        Cache::put("email_verification:$email", $code, 300);

        SendEmailJob::dispatch($email, $code);
    }
}
