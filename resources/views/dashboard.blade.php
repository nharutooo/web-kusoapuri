<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center py-2">
            {{-- 左側：ロゴ風テキスト --}}
            <h2 class="font-bold text-2xl text-gray-800 tracking-tight flex items-center gap-2">
                <span class="bg-blue-500 text-white rounded p-1 text-sm">GAMES</span>
                <span>Dashboard</span>
            </h2>

            {{-- 右側：プレイヤーステータス（Switch風） --}}
            <div class="flex items-center gap-4 text-sm font-bold text-gray-600">
                <div class="flex items-center gap-2 bg-white px-3 py-1 rounded-full shadow-sm border">
                    <span class="text-yellow-500">★</span>
                    <span>RANK: 1</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-blue-400 to-blue-600 border-2 border-white shadow-sm"></div>
                    <span>{{ Auth::user()->name }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- ■■■ メインコンテンツ（背景：クリーンなグレー） ■■■ --}}
    <div class="py-12 min-h-screen bg-gray-50 selection:bg-blue-500 selection:text-white">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- ゲーム一覧ラベル --}}
            <div class="mb-6 flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-700 border-l-4 border-blue-500 pl-3">
                    {{ __('Game Library') }}
                </h3>
                <span class="text-xs text-gray-400 font-mono">{{ __('Ver 1.2.0') }}</span>
            </div>

            {{-- ■ ゲームタイル（Gridレイアウト） ■ --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                
                {{-- 1. じゃんけん --}}
                <a href="{{ route('games.janken') }}" onmouseenter="playHover()" onclick="playDecide()" 
                   class="group relative bg-white rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 ease-out transform hover:-translate-y-1 hover:scale-105 overflow-hidden ring-4 ring-transparent hover:ring-red-400">
                    {{-- サムネイルエリア --}}
                    <div class="h-40 bg-gradient-to-br from-red-500 to-orange-400 flex items-center justify-center relative overflow-hidden">
                        <span class="text-6xl drop-shadow-lg filter group-hover:brightness-110 transition">✊</span>
                        <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition duration-300"></div>
                    </div>
                    {{-- 情報エリア --}}
                    <div class="p-5">
                        <h4 class="font-bold text-gray-800 text-lg mb-1 group-hover:text-red-500 transition-colors">
                            {{ __('Janken Battle') }}
                        </h4>
                        <p class="text-xs text-gray-500 leading-relaxed">
                            {{ __('Classic rock-paper-scissors with intense animations.') }}
                        </p>
                    </div>
                </a>

                {{-- 2. 早押し --}}
                <a href="{{ route('games.hayaoshi') }}" onmouseenter="playHover()" onclick="playDecide()"
                   class="group relative bg-white rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 ease-out transform hover:-translate-y-1 hover:scale-105 overflow-hidden ring-4 ring-transparent hover:ring-blue-400">
                    <div class="h-40 bg-gradient-to-br from-blue-500 to-cyan-400 flex items-center justify-center relative overflow-hidden">
                        <span class="text-6xl drop-shadow-lg">⚡</span>
                        <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition duration-300"></div>
                    </div>
                    <div class="p-5">
                        <h4 class="font-bold text-gray-800 text-lg mb-1 group-hover:text-blue-500 transition-colors">
                            {{ __('Speed Push') }}
                        </h4>
                        <p class="text-xs text-gray-500 leading-relaxed">
                            {{ __('Test your reflexes. Milliseconds matter.') }}
                        </p>
                    </div>
                </a>

                {{-- 3. 蛇（Snake） --}}
                <a href="{{ route('games.hebi') }}" onmouseenter="playHover()" onclick="playDecide()"
                   class="group relative bg-white rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 ease-out transform hover:-translate-y-1 hover:scale-105 overflow-hidden ring-4 ring-transparent hover:ring-green-400">
                    <div class="h-40 bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center relative overflow-hidden">
                        <span class="text-6xl drop-shadow-lg">🐍</span>
                        <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition duration-300"></div>
                    </div>
                    <div class="p-5">
                        <h4 class="font-bold text-gray-800 text-lg mb-1 group-hover:text-green-500 transition-colors">
                            {{ __('Solid Snake') }}
                        </h4>
                        <p class="text-xs text-gray-500 leading-relaxed">
                            {{ __('Grow longer without hitting yourself.') }}
                        </p>
                    </div>
                </a>

                {{-- 4. 神経衰弱 --}}
                <a href="{{ route('games.shinkei') }}" onmouseenter="playHover()" onclick="playDecide()"
                   class="group relative bg-white rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 ease-out transform hover:-translate-y-1 hover:scale-105 overflow-hidden ring-4 ring-transparent hover:ring-purple-400">
                    <div class="h-40 bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center relative overflow-hidden">
                        <span class="text-6xl drop-shadow-lg">🃏</span>
                        <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition duration-300"></div>
                    </div>
                    <div class="p-5">
                        <h4 class="font-bold text-gray-800 text-lg mb-1 group-hover:text-purple-500 transition-colors">
                            {{ __('Memory Match') }}
                        </h4>
                        <p class="text-xs text-gray-500 leading-relaxed">
                            {{ __('Train your brain. Find the matching pairs.') }}
                        </p>
                    </div>
                </a>
            </div>

            {{-- ■ システムメニュー（パスワードマネージャー） ■ --}}
            <div class="mt-8 border-t border-gray-200 pt-8">
                <h3 class="text-sm font-bold text-gray-500 mb-4 uppercase tracking-wider">
                    {{ __('System Apps') }}
                </h3>
                
                <a href="{{ route('password.index') }}" onmouseenter="playHover()" onclick="playDecide()"
                   class="group flex items-center bg-white p-4 rounded-xl shadow-sm border border-gray-100 hover:border-gray-300 hover:shadow-md transition duration-200">
                    <div class="bg-gray-100 p-3 rounded-lg text-gray-600 group-hover:bg-gray-800 group-hover:text-white transition-colors">
                        <span class="text-2xl">🔐</span>
                    </div>
                    <div class="ml-4">
                        <h4 class="font-bold text-gray-800 group-hover:text-blue-600 transition-colors">
                            {{ __('Password Manager') }}
                        </h4>
                        <p class="text-xs text-gray-500">
                            {{ __('Manage your saved credentials.') }}
                        </p>
                    </div>
                    <div class="ml-auto text-gray-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </a>
            </div>
            
        </div>
    </div>

    {{-- ■ 音源読み込み ■ --}}
    <audio id="bgm" loop>
        <source src="/sounds/bgm_home.mp3" type="audio/mpeg">
    </audio>
    <audio id="se-hover">
        <source src="/sounds/se_hover.mp3" type="audio/mpeg">
    </audio>
    <audio id="se-decide">
        <source src="/sounds/se_decide.mp3" type="audio/mpeg">
    </audio>

    <script>
        document.body.addEventListener('click', function() {
            const bgm = document.getElementById('bgm');
            bgm.volume = 0.2; // BGMは少し控えめに
            bgm.play().catch(e => {});
        }, { once: true });

        function playHover() {
            const se = document.getElementById('se-hover');
            se.currentTime = 0;
            se.volume = 0.4; // 決定音より少し小さく
            se.play().catch(e => {});
        }

        function playDecide() {
            const se = document.getElementById('se-decide');
            se.currentTime = 0;
            se.volume = 0.6;
            se.play().catch(e => {});
        }
    </script>
</x-app-layout>