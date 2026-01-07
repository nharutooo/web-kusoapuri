@php
    $isTutorial = $initialTutorial ?? false;
    $toggleTarget = $isTutorial ? route('hayaoshi.main') : route('hayaoshi.tutorial');
    $toggleLabel = $isTutorial ? '本番へ' : 'チュートリアルへ';
    $title = $isTutorial ? '早押しゲーム（デモプレイ）' : '早押しゲーム（本番）';
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg relative">
                <button
                    id="modeToggleButton"
                    type="button"
                    data-target="{{ $toggleTarget }}"
                    class="absolute right-4 top-4 px-3 py-1.5 text-sm rounded-md border border-red-200 dark:border-red-700 text-red-700 dark:text-red-200 bg-red-50 dark:bg-red-800/30 hover:bg-red-100 dark:hover:bg-red-800/50 transition"
                >
                    {{ $toggleLabel }}
                </button>
                <div class="p-8 flex flex-col items-center gap-6 text-center">
                    <div class="space-y-2 min-h-[5.5rem]">
                        <p id="statusText" class="text-sm text-gray-600 dark:text-gray-300 min-h-[1.5rem]"></p>
                        <p id="cueText" class="text-2xl font-bold text-red-600 dark:text-red-400 min-h-[2.25rem]">-</p>
                        <p id="resultText" class="text-lg font-semibold text-gray-800 dark:text-gray-100 min-h-[1.75rem]"></p>
                    </div>

                    <button
                        id="startButton"
                        type="button"
                        class="px-6 py-3 rounded-md bg-gray-900 text-white font-semibold shadow hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-700 transition"
                    >
                        スタート
                    </button>

                    <div
                        id="buttonArea"
                        class="relative w-full max-w-xl h-72 sm:h-80 border border-gray-200 dark:border-gray-700 rounded-md overflow-hidden bg-white/60 dark:bg-gray-900/40"
                    >
                        <img
                            id="hayaoshiButton"
                            src="{{ asset('hayaoshi_button.png') }}"
                            alt="早押しボタン"
                            class="absolute left-0 top-0 w-full max-w-[32px] cursor-pointer select-none transition-transform duration-300 ease-out opacity-0"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (() => {
            const startButton = document.getElementById('startButton');
            const hayaoshiButton = document.getElementById('hayaoshiButton');
            const modeToggleButton = document.getElementById('modeToggleButton');
            const statusText = document.getElementById('statusText');
            const cueText = document.getElementById('cueText');
            const resultText = document.getElementById('resultText');
            const buttonArea = document.getElementById('buttonArea');

            const initialTutorial = @json($isTutorial);
            const tutorialMessage = 'チュートリアル: スタートを押して「今だ！」が出たら赤いボタンをクリックしてみよう';

            // 状態管理
            let state = 'idle'; // idle | waiting | ready | done
            let running = false; // スタート後に合図ループを回しているか
            let isTutorial = initialTutorial;
            let cueTimer = null;
            let readyAt = null;
            let startAt = null;
            let moveInterval = null;
            let moveStopTimer = null;

            const setState = (next) => {
                state = next;
            };

            const defaultMessage = () => (isTutorial ? tutorialMessage : '');
            const startLabel = () => (isTutorial ? 'デモ開始' : 'スタート');

            // 位置更新を止める
            const stopMovement = () => {
                clearInterval(moveInterval);
                moveInterval = null;
            };

            // ボタンをランダムな位置へ移動
            const moveButtonRandomly = () => {
                if (!buttonArea || !hayaoshiButton) return;

                const areaRect = buttonArea.getBoundingClientRect();
                const btnRect = hayaoshiButton.getBoundingClientRect();

                const maxX = Math.max(areaRect.width - btnRect.width, 0);
                const maxY = Math.max(areaRect.height - btnRect.height, 0);

                const x = Math.random() * maxX;
                const y = Math.random() * maxY;

                hayaoshiButton.style.transform = `translate(${x}px, ${y}px)`;
            };

            // ランダム移動を開始
            const startMovement = () => {
                if (isTutorial) return; // チュートリアルでは動かさない
                stopMovement();
                moveButtonRandomly(); // 初回即時
                moveInterval = setInterval(moveButtonRandomly, 600);
            };

            // ゲームを初期状態へ戻す
            const resetGame = (message = null) => {
                clearTimeout(cueTimer);
                clearTimeout(moveStopTimer);
                cueTimer = null;
                moveStopTimer = null;
                readyAt = null;
                startAt = null;
                running = false;
                stopMovement();
                // 中央に戻す
                if (buttonArea && hayaoshiButton) {
                    const areaRect = buttonArea.getBoundingClientRect();
                    const btnRect = hayaoshiButton.getBoundingClientRect();
                    const x = Math.max((areaRect.width - btnRect.width) / 2, 0);
                    const y = Math.max((areaRect.height - btnRect.height) / 2, 0);
                    hayaoshiButton.style.transform = `translate(${x}px, ${y}px)`;
                }
                setState('idle');
                startButton.textContent = startLabel();
                statusText.textContent = '';
                cueText.textContent = '-';
                const nextMessage = message ?? defaultMessage();
                resultText.textContent = nextMessage;
            };

            // 次の合図を予約（最低5秒間隔）
            const scheduleNextCue = () => {
                const waitMs = 5000 + Math.floor(Math.random() * 3000); // 5-8秒
                clearTimeout(cueTimer);
                cueTimer = setTimeout(runCue, waitMs);
            };

            // 合図を出し、1秒間のみ計測可能
            const runCue = () => {
                if (!running) return;
                cueText.textContent = '今だ！';
                statusText.textContent = '';
                readyAt = performance.now();
                setState('ready');
                startMovement();

                clearTimeout(moveStopTimer);
                moveStopTimer = setTimeout(() => {
                    stopMovement();
                    if (running && state === 'ready') {
                        // 押されなかったので次の合図へ
                        readyAt = null;
                        setState('waiting');
                        cueText.textContent = '待機中...';
                        statusText.textContent = '';
                        scheduleNextCue();
                    }
                }, 1000); // 合図から1秒だけ有効
            };

            // ゲーム開始（スタートボタン押下）
            const startGame = () => {
                resetGame(null);
                running = true;
                setState('waiting');
                startButton.textContent = 'ストップ';
                statusText.textContent = '';
                startAt = performance.now();
                cueText.textContent = '待機中...';
                resultText.textContent = isTutorial
                    ? 'デモプレイ: 「今だ！」が出たら赤いボタンをクリックしてみてください。'
                    : '';
                scheduleNextCue();
            };

            // スタート／ストップのトグル
            const handleStartClick = () => {
                if (state === 'idle' || state === 'done') {
                    startGame();
                    return;
                }
                // ストップ扱いでリセット
                resetGame('キャンセルしました');
            };

            // 早押しボタンが押されたとき
            const handleHayaoshiClick = () => {
                if (state === 'waiting') {
                    resetGame('何してるの？');
                    return;
                }

                if (state === 'ready' && readyAt !== null) {
                    if (isTutorial) {
                        const reactionMs = performance.now() - readyAt;
                        const reactionSec = (reactionMs / 1000).toFixed(3);
                        resultText.textContent = `今だ！から${reactionSec}秒で押しました`;
                    } else {
                        const totalMs = performance.now() - (startAt ?? performance.now());
                        const seconds = (totalMs / 1000).toFixed(3);
                        resultText.textContent = `あなたはいま、${seconds}秒、無駄にしました`;
                    }
                    statusText.textContent = '';
                    setState('done');
                    stopMovement();
                    clearTimeout(cueTimer);
                    clearTimeout(moveStopTimer);
                    running = false;
                    startButton.textContent = startLabel();
                    return;
                }

                // idle/done時は何もしない
            };

            startButton.addEventListener('click', handleStartClick);
            hayaoshiButton.addEventListener('click', handleHayaoshiClick);
            modeToggleButton.addEventListener('click', () => {
                const target = modeToggleButton.dataset.target;
                if (target) {
                    window.location.href = target;
                }
            });

            // 初期位置セット（ロード後に中央へ）
            const centerIfNeeded = () => {
                resetGame(null);
                hayaoshiButton.classList.remove('opacity-0');
            };
            if (hayaoshiButton.complete) {
                centerIfNeeded();
            } else {
                hayaoshiButton.addEventListener('load', centerIfNeeded, { once: true });
            }

            // リサイズ時も位置を更新
            window.addEventListener('resize', moveButtonRandomly);

            // 画面離脱時のクリーンアップ
            window.addEventListener('beforeunload', stopMovement);
        })();
    </script>
</x-app-layout>
