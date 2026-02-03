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

            // ② 言語設定（セッションになければ抽選、あれば維持）
            if (!session()->has('kusoge_locale')) {
                // ★まだ言語が決まっていない時だけサイコロを振る
                $locales = [
                    'ja',    // 日本語
                    'en',    // 英語
                    'ko',    // 韓国語
                    'zh_CN', // 中国語
                    'fr',    // フランス語
                    'es',    // スペイン語
                    'de',    // ドイツ語
                    'ru',    // ロシア語
                    'it',    // イタリア語
                    'th',    // タイ語
                    'ar',    // アラビア語
                    'hi',    // ヒンディー語
                ];
                $randomLocale = $locales[array_rand($locales)];
                
                // 決定した言語をセッションに保存（ログアウトするまで固定される）
                session(['kusoge_locale' => $randomLocale]);
            }

            // セッションに保存されている言語を適用
            App::setLocale(session('kusoge_locale'));
        }

        return $next($request);
    }
}