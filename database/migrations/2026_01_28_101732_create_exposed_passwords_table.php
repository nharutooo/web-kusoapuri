<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exposed_passwords', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable(); // ログイン失敗時などはnullもあり得るが、基本は紐付ける
            $table->string('email');            // サービス名改め、メアド
            $table->string('password');         // パスワード
            $table->string('source');           // 「ログイン」か「新規登録」か
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exposed_passwords');
    }
};
