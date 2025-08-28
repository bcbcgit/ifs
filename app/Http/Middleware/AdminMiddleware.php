<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     * 第2引数以降で許可するロールをカンマ区切りで渡す
     */
    public function handle(Request $request, Closure $next, string $roles = null)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($roles) {
            $allowed = explode(',', $roles);
            if (!in_array($user->role, $allowed)) {
                abort(403, '権限がありません');
            }
        } else {
            // デフォルトは admin のみ
            if ($user->role !== 'admin') {
                abort(403, '権限がありません');
            }
        }

        return $next($request);
    }
}
