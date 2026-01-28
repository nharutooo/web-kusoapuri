<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

class KusogeChaosMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // ログインしている場合のみ発動
        if (Auth::check()) {
            // ① 3分タイマーチェック
            $expiresAt = session('kusoge_expires_at');
            if ($expiresAt && now()->greaterThan($expiresAt)) {
                // 時間切れなら強制ログアウト
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                // ログイン画面へ飛ばして煽る
                return redirect('/login')->with('status', 'おかえりww');
            }

            // ② 言語ランダム適用
            $locale = session('kusoge_locale', 'ja');
            App::setLocale($locale);
        }

        return $next($request);
    }
}