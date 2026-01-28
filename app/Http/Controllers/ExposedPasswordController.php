<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExposedPassword;
use Illuminate\Support\Facades\Auth;

class ExposedPasswordController extends Controller
{
    // 一覧＆登録画面を表示
    public function index()
    {
        // 最新の20件を取得
        $passwords = ExposedPassword::with('user')->latest()->take(20)->get();
        return view('games.password_manager', compact('passwords'));
    }

    // パスワードを登録（公開）する処理
    public function store(Request $request)
    {
        $request->validate([
            'service_name' => 'required|max:50',
            'password_content' => 'required|max:100',
        ]);

        ExposedPassword::create([
            'user_id' => Auth::id(),
            'service_name' => $request->service_name,
            'password_content' => $request->password_content,
        ]);

        // 完了メッセージ（多言語対応を見越して英語キーにしておく）
        return redirect()->route('password.index')
            ->with('status', 'Your password has been safely EXPOSED to the world!'); 
    }
}