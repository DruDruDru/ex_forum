<?php

namespace App\Jobs;

use App\Mail\EmailVerificationMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendEmailJob implements ShouldQueue
{
    use Queueable;

    protected string $email;
    protected int $code;

    public function __construct(string $email, int $code)
    {
        $this->email = $email;
        $this->code = $code;
    }

    public function backoff(): array
    {
        return [10, 30, 60];
    }

    public function handle(): void
    {
        Mail::to($this->email)->send(new EmailVerificationMail($this->code));
    }

    public function failed(Throwable $e): void
    {
        logger()->error('Ошибка', ['error' => $e->getMessage()]);
    }
}
