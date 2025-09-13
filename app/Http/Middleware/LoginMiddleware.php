<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class LoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = User::where('email', $request->input('email'))->first();

        if ($user && !$user->email_verified_at) {
            return response()->json([
                'message' => 'Вы не подтвердили учётную запись'
            ], Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
