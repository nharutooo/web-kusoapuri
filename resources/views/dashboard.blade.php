<x-app-layout>
    {{-- ■ Google Fonts読み込み（ドット文字） ■ --}}
    <link href="https://fonts.googleapis.com/css2?family=DotGothic16&display=swap" rel="stylesheet">
    
    {{-- ■ カスタムCSS（走査線エフェクト & ドットフォント強制） ■ --}}
    <style>
        body, .font-dot {
            font-family: 'DotGothic16', sans-serif !important;
        }
        /* ブラウン管風の走査線 */
        .scanlines {
            background: linear-gradient(
                to bottom,
                rgba(255,255,255,0),
                rgba(255,255,255,0) 50%,
                rgba(0,0,0,0.2) 50%,
                rgba(0,0,0,0.2)
            );
            background-size: 100% 4px;
            position: fixed;
            pointer-events: none;
            inset: 0;
            z-index: 99999; /* 最前面 */
        }
        /* ホバー時の反転エフェクト */
        .nes-btn:hover {
            background-color: white !important;
            color: black !important;
        }
        .nes-btn:hover * {
            color: black !important;
        }
    </style>

    {{-- ブラウン管エフェクト --}}
    <div class="scanlines"></div>

    <x-slot name="header">
        <div class="flex justify-between items-center py-4 border-b-4 border-black">
            {{-- 左側：ロゴ --}}
            <h2 class="font-bold text-3xl text-black tracking-widest flex items-center gap-4">
                <span class="bg-black text-white px-2 py-1 text-sm border-2 border-black">8-BIT</span>
                <span>{{ __('Dashboard') }}</span>
            </h2>

            {{-- 右側：ステータス --}}
            <div class="flex items-center gap-6 text-lg font-bold text-black">
                <div class="flex items-center gap-2">
                    <span class="text-yellow-600">★</span>
                    <span>LV.1</span> {{-- "RANK"がないので"LV"等の普遍的な表記か、記号のみにする --}}
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-black border-2 border-black"></div>
                    <span class="uppercase">{{ Auth::user()->name }}</span>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- ■■■ メインコンテンツ（黒背景・ドット文字） ■■■ --}}
    <div class="py-12 min-h-screen bg-black text-white selection:bg-green-500 selection:text-black">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- ラベル --}}
            <div class="mb-8 flex items-end justify-between border-b-4 border-white pb-2">
                <h3 class="text-2xl text-white pl-2">
                    ▶ {{ __('Game Library') }}
                </h3>
                <span class="text-sm text-gray-400 mb-1">{{ __('Ver 1.2.0') }}</span>
            </div>

            {{-- ■ ゲームタイル（NESスタイル） ■ --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
                
                {{-- 1. じゃんけん --}}
                <a href="{{ route('games.janken') }}" onmouseenter="playHover()" onclick="playDecide(event, this.href)" 
                   class="nes-btn group relative block bg-black border-4 border-white p-2 hover:translate-y-1 transition-none">
                    {{-- ドット絵風枠 --}}
                    <div class="h-32 bg-red-700 flex items-center justify-center border-b-4 border-white group-hover:bg-black group-hover:border-black transition-none">
                        <span class="text-6xl grayscale group-hover:grayscale-0">✊</span>
                    </div>
                    <div class="p-4">
                        <h4 class="font-bold text-xl mb-2 text-red-500 group-hover:text-black">
                            {{ __('Janken Battle') }}
                        </h4>
                        <p class="text-xs leading-relaxed text-gray-300 group-hover:text-black">
                            {{ __('Classic rock-paper-scissors with intense animations.') }}
                        </p>
                    </div>
                </a>

                {{-- 2. 早押し --}}
                <a href="{{ route('games.hayaoshi') }}" onmouseenter="playHover()" onclick="playDecide(event, this.href)"
                   class="nes-btn group relative block bg-black border-4 border-white p-2 hover:translate-y-1 transition-none">
                    <div class="h-32 bg-blue-700 flex items-center justify-center border-b-4 border-white group-hover:bg-black group-hover:border-black transition-none">
                        <span class="text-6xl grayscale group-hover:grayscale-0">⚡</span>
                    </div>
                    <div class="p-4">
                        <h4 class="font-bold text-xl mb-2 text-blue-500 group-hover:text-black">
                            {{ __('Speed Push') }}
                        </h4>
                        <p class="text-xs leading-relaxed text-gray-300 group-hover:text-black">
                            {{ __('Test your reflexes. Milliseconds matter.') }}
                        </p>
                    </div>
                </a>

                {{-- 3. 蛇 --}}
                <a href="{{ route('games.hebi') }}" onmouseenter="playHover()" onclick="playDecide(event, this.href)"
                   class="nes-btn group relative block bg-black border-4 border-white p-2 hover:translate-y-1 transition-none">
                    <div class="h-32 bg-green-700 flex items-center justify-center border-b-4 border-white group-hover:bg-black group-hover:border-black transition-none">
                        <span class="text-6xl grayscale group-hover:grayscale-0">🐍</span>
                    </div>
                    <div class="p-4">
                        <h4 class="font-bold text-xl mb-2 text-green-500 group-hover:text-black">
                            {{ __('Solid Snake') }}
                        </h4>
                        <p class="text-xs leading-relaxed text-gray-300 group-hover:text-black">
                            {{ __('Grow longer without hitting yourself.') }}
                        </p>
                    </div>
                </a>

                {{-- 4. 神経衰弱 --}}
                <a href="{{ route('games.shinkei') }}" onmouseenter="playHover()" onclick="playDecide(event, this.href)"
                   class="nes-btn group relative block bg-black border-4 border-white p-2 hover:translate-y-1 transition-none">
                    <div class="h-32 bg-purple-700 flex items-center justify-center border-b-4 border-white group-hover:bg-black group-hover:border-black transition-none">
                        <span class="text-6xl grayscale group-hover:grayscale-0">🃏</span>
                    </div>
                    <div class="p-4">
                        <h4 class="font-bold text-xl mb-2 text-purple-500 group-hover:text-black">
                            {{ __('Memory Match') }}
                        </h4>
                        <p class="text-xs leading-relaxed text-gray-300 group-hover:text-black">
                            {{ __('Train your brain. Find the matching pairs.') }}
                        </p>
                    </div>
                </a>
            </div>

            {{-- ■ システムメニュー（パスワードマネージャー） ■ --}}
            <div class="mt-12 border-t-4 border-white pt-8">
                <h3 class="text-lg text-gray-400 mb-6 uppercase tracking-widest">
                    {{ __('System Apps') }}
                </h3>
                
                {{-- data-urlを使ってエラー回避 & ウィンドウ表示 --}}
                <a href="#" 
                   data-url="{{ route('password.index') }}"
                   onmouseenter="playHover()" 
                   onclick="openApp(event, this.dataset.url)"
                   class="nes-btn group flex items-center bg-black border-4 border-white p-6 hover:bg-white hover:text-black transition-none">
                    <div class="border-4 border-white p-2 bg-gray-800 text-white group-hover:bg-black group-hover:text-white group-hover:border-black">
                        <span class="text-3xl">🔐</span>
                    </div>
                    <div class="ml-6">
                        <h4 class="font-bold text-2xl mb-1 group-hover:text-black">
                            {{ __('Password Manager') }}
                        </h4>
                        <p class="text-sm text-gray-400 group-hover:text-black">
                            {{ __('Manage your saved credentials.') }}
                        </p>
                    </div>
                    <div class="ml-auto text-white group-hover:text-black text-2xl animate-pulse">
                        ▶
                    </div>
                </a>
            </div>
            
        </div>
    </div>

    {{-- ■■■ 入場ゲート（手書き画像 + ファミコン風ボタン） ■■■ --}}
    <div id="enter-screen" class="fixed inset-0 bg-white z-[9999] flex flex-col items-center justify-center transition-opacity duration-100">
        {{-- 背景画像 --}}
        <img src="/images/kusoapuri.png" class="absolute inset-0 w-full h-full object-contain opacity-100 bg-white">
        
        {{-- スタートボタン（言葉は辞書にある "Continue" を使用） --}}
        <div class="relative z-10 mt-64 flex flex-col items-center">
            <button onclick="enterGame()" class="group relative px-8 py-4 bg-black text-white font-dot text-2xl border-4 border-white hover:bg-white hover:text-black hover:border-black shadow-[4px_4px_0px_0px_#000] hover:translate-x-1 hover:translate-y-1 hover:shadow-none transition-none">
                ▶ {{ __('Continue') }}
            </button>
        </div>
    </div>

    {{-- ■ アプリ表示用オーバーレイ（iframe・レトロ枠） ■ --}}
    <div id="app-overlay" class="fixed inset-0 bg-black/80 z-[50] hidden flex items-center justify-center p-4 backdrop-blur-none transition-opacity duration-100 opacity-0">
        {{-- ウィンドウ枠 --}}
        <div class="bg-black w-full h-full max-w-6xl max-h-[90vh] border-4 border-white flex flex-col relative transform scale-95 transition-transform duration-100" id="app-window">
            
            {{-- ウィンドウヘッダー --}}
            <div class="bg-white text-black px-4 py-2 flex justify-between items-center shrink-0 border-b-4 border-white">
                <span class="font-bold flex items-center gap-4 text-xl">
                    <span>🔐</span> {{ __('System Apps') }}
                </span>
                <button onclick="closeApp()" class="text-black hover:bg-black hover:text-white border-2 border-black w-8 h-8 flex items-center justify-center font-bold text-xl">
                    X
                </button>
            </div>

            {{-- アプリ表示エリア --}}
            <iframe id="app-frame" src="" class="w-full h-full border-0 bg-gray-100"></iframe>
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
        // ★入場処理
        function enterGame() {
            const se = document.getElementById('se-decide');
            se.volume = 0.6;
            se.currentTime = 0;
            se.play().catch(()=>{});

            const bgm = document.getElementById('bgm');
            bgm.volume = 0.2;
            bgm.play().then(() => {
                console.log("BGM Start!");
            }).catch(e => {});

            // パッと消える（フェードなし）
            const screen = document.getElementById('enter-screen');
            screen.style.opacity = '0';
            setTimeout(() => {
                screen.style.display = 'none';
            }, 100);
        }

        // ホバー音
        function playHover() {
            const se = document.getElementById('se-hover');
            se.currentTime = 0;
            se.volume = 0.4;
            se.play().catch(e => {});
        }

        // ゲーム遷移
        function playDecide(event, url) {
            event.preventDefault();
            const se = document.getElementById('se-decide');
            se.currentTime = 0;
            se.volume = 0.6;
            se.play().catch(e => {});

            setTimeout(() => {
                window.location.href = url;
            }, 400); 
        }

        // アプリウィンドウ表示
        function openApp(event, url) {
            event.preventDefault(); 
            const se = document.getElementById('se-decide');
            se.currentTime = 0;
            se.volume = 0.6;
            se.play().catch(()=>{});

            const frame = document.getElementById('app-frame');
            frame.src = url;

            const overlay = document.getElementById('app-overlay');
            const windowEl = document.getElementById('app-window');
            
            overlay.classList.remove('hidden');
            // レトロなのでアニメーションは高速に
            setTimeout(() => {
                overlay.classList.remove('opacity-0');
                windowEl.classList.remove('scale-95');
                windowEl.classList.add('scale-100');
            }, 10);
        }

        // アプリウィンドウ閉じる
        function closeApp() {
            const se = document.getElementById('se-hover');
            se.currentTime = 0;
            se.play().catch(()=>{});

            const overlay = document.getElementById('app-overlay');
            const windowEl = document.getElementById('app-window');

            overlay.classList.add('opacity-0');
            windowEl.classList.remove('scale-100');
            windowEl.classList.add('scale-95');

            setTimeout(() => {
                overlay.classList.add('hidden');
                document.getElementById('app-frame').src = "";
            }, 100);
        }
    </script>
</x-app-layout>