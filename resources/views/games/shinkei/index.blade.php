<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>神経衰弱</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=M+PLUS+Rounded+1c:wght@700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <style>
        body { font-family: 'M PLUS Rounded 1c', sans-serif; }
        .perspective { perspective: 1000px; }
        .preserve-3d { transform-style: preserve-3d; }
        .backface-hidden { backface-visibility: hidden; }
        .rotate-y-180 { transform: rotateY(180deg); }
        .flipped { transform: rotateY(180deg); }
        .matched-card { opacity: 0.4; transform: rotateY(180deg) scale(0.85); transition: all 0.6s; pointer-events: none; filter: grayscale(1); }
        @keyframes shake {
            0%, 100% { transform: translateX(0) rotateY(180deg); }
            25% { transform: translateX(-8px) rotateY(180deg); }
            75% { transform: translateX(8px) rotateY(180deg); }
        }
        .shake { animation: shake 0.3s; }
        .shuffle-anim { transition: all 0.5s ease-in-out; transform: scale(0.5) rotate(360deg); opacity: 0; }
    </style>
</head>
<body class="bg-zinc-900 text-white min-h-screen py-8">

    <div id="win-overlay" class="fixed inset-0 bg-black/80 backdrop-blur-md z-50 flex items-center justify-center hidden opacity-0 transition-all duration-500">
        <div class="bg-white p-8 rounded-3xl shadow-2xl text-center max-w-sm mx-4 transform scale-90 transition-all" id="win-modal">
            <div class="text-6xl mb-4">🙄</div>
            <h2 class="text-3xl font-bold text-zinc-800 mb-2">やっと終わった？</h2>
            <p class="text-zinc-500 mb-6">まあ、お疲れ様です...</p>
            <div class="bg-zinc-100 rounded-2xl p-4 mb-8">
                <p class="text-xs uppercase font-bold text-zinc-400">Total Moves</p>
                <p id="final-moves" class="text-5xl font-black text-zinc-800">0</p>
            </div>
            <button onclick="window.location.reload()" class="w-full bg-indigo-600 text-white py-4 rounded-xl font-bold text-lg hover:bg-indigo-700 shadow-lg active:scale-95 transition">
                もう一度挑む
            </button>
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-4">
        <h1 class="text-4xl font-black text-center mb-2 text-indigo-500 italic">神経衰弱</h1>
        <p class="text-center text-zinc-500 text-sm mb-6 font-bold">KUSO-EDITION v1.0</p>
        
        <div class="flex justify-center gap-2 mb-8">
            @foreach([1, 2, 3, 4] as $l)
                <a href="?level={{ $l }}" 
                   class="px-5 py-2 rounded-full font-bold transition-all {{ $level == $l ? 'bg-indigo-600 text-white ring-4 ring-indigo-900 scale-110' : 'bg-zinc-800 text-zinc-500 hover:bg-zinc-700' }}">
                    Lv.{{ $l }}
                </a>
            @endforeach
        </div>

        <div class="h-12 flex items-center justify-center mb-4">
            <p id="heckle-message" class="text-pink-500 font-bold text-xl animate-pulse text-center">
                早く始めてよ (Hurry up and start)
            </p>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-8">
            <div class="bg-zinc-800 p-4 rounded-2xl border-b-4 border-zinc-700">
                <span class="text-xs text-zinc-500 block uppercase">手数 (Moves)</span>
                <span id="move-count" class="text-3xl font-black text-indigo-400">0</span>
            </div>
            <div class="bg-zinc-800 p-4 rounded-2xl border-b-4 border-zinc-700">
                <span class="text-xs text-zinc-500 block uppercase">連敗 (Mistake Streak)</span>
                <span id="streak-count" class="text-3xl font-black text-red-500">0</span>
            </div>
        </div>
        
        <div class="grid {{ $level == 1 ? 'grid-cols-2' : ($level == 4 ? 'grid-cols-5' : 'grid-cols-4') }} gap-3" id="game-board">
            @foreach($cards as $index => $emoji)
                <div class="card-container perspective h-24 sm:h-32 cursor-pointer" onclick="flipCard(this)" data-emoji="{{ $emoji }}">
                    <div class="card-inner relative w-full h-full transition-transform duration-500 preserve-3d">
                        <div class="card-back absolute inset-0 bg-zinc-700 rounded-xl flex items-center justify-center text-zinc-500 text-3xl backface-hidden border-b-4 border-zinc-900">
                            ?
                        </div>
                        <div class="card-front absolute inset-0 bg-white rounded-xl flex items-center justify-center text-3xl sm:text-4xl backface-hidden rotate-y-180">
                            {{ $emoji }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-12 flex flex-col items-center gap-4">
            <p id="match-status" class="text-zinc-500 font-bold">揃えた数: 0 / {{ $pairCount }}</p>
            <button onclick="window.location.reload()" class="text-zinc-600 hover:text-white transition font-bold text-sm">
                🔄 諦めて最初からやり直す
            </button>
        </div>
    </div>

    <script>
        let flippedCards = [];
        let lockBoard = false;
        let moves = 0;
        let matches = 0;
        let mistakeStreak = 0;
        const totalPairs = {{ $pairCount }};

        const heckles = {
            10: "まだそれだけ？笑",
            20: "記憶力、大丈夫そ？",
            30: "鳥頭なのかな？ピヨピヨ",
            40: "わざと間違えてるでしょ",
            50: "もしかして、寝てる？",
            60: "伝説のヘタクソ降臨ｗｗｗ"
        };

        function flipCard(card) {
            const inner = card.querySelector('.card-inner');
            if (lockBoard || inner.classList.contains('flipped') || card.classList.contains('matched')) return;

            inner.classList.add('flipped');
            flippedCards.push(card);

            if (flippedCards.length === 2) {
                moves++;
                document.getElementById('move-count').innerText = moves;
                if (heckles[moves]) document.getElementById('heckle-message').innerText = heckles[moves];
                checkMatch();
            }
        }

        function checkMatch() {
            lockBoard = true;
            const isMatch = flippedCards[0].dataset.emoji === flippedCards[1].dataset.emoji;

            if (isMatch) {
                mistakeStreak = 0;
                document.getElementById('streak-count').innerText = mistakeStreak;
                setTimeout(() => {
                    flippedCards.forEach(card => {
                        card.classList.add('matched');
                        card.querySelector('.card-inner').classList.add('matched-card');
                    });
                    matches++;
                    document.getElementById('match-status').innerText = `揃えた数: ${matches} / ${totalPairs}`;
                    if (matches === totalPairs) showWinScreen();
                    resetBoard();
                }, 400);
            } else {
                mistakeStreak++;
                document.getElementById('streak-count').innerText = mistakeStreak;
                
                if (mistakeStreak >= 3) {
                    kusoShuffle();
                }

                flippedCards.forEach(card => card.querySelector('.card-inner').classList.add('shake'));
                setTimeout(() => {
                    flippedCards.forEach(card => {
                        card.querySelector('.card-inner').classList.remove('flipped', 'shake');
                    });
                    resetBoard();
                }, 1000);
            }
        }

        function kusoShuffle() {
            const msg = document.getElementById('heckle-message');
            msg.innerText = "下手すぎｗｗシャッフルするね！";
            msg.classList.add('text-yellow-400');
            
            setTimeout(() => {
                const board = document.getElementById('game-board');
                const cards = Array.from(board.children);
                
                // Shuffle logic
                for (let i = cards.length - 1; i > 0; i--) {
                    const j = Math.floor(Math.random() * (i + 1));
                    board.appendChild(cards[j]);
                }
                mistakeStreak = 0;
                document.getElementById('streak-count').innerText = mistakeStreak;
                setTimeout(() => msg.classList.remove('text-yellow-400'), 2000);
            }, 600);
        }

        function showWinScreen() {
            const end = Date.now() + 2000;
            (function frame() {
                confetti({ particleCount: 2, angle: 60, spread: 55, origin: { x: 0 }, colors: ['#6366f1', '#ec4899'] });
                confetti({ particleCount: 2, angle: 120, spread: 55, origin: { x: 1 }, colors: ['#6366f1', '#ec4899'] });
                if (Date.now() < end) requestAnimationFrame(frame);
            }());

            const overlay = document.getElementById('win-overlay');
            const modal = document.getElementById('win-modal');
            document.getElementById('final-moves').innerText = moves;
            overlay.classList.remove('hidden');
            setTimeout(() => {
                overlay.classList.remove('opacity-0');
                modal.classList.remove('scale-90');
            }, 10);
        }

        function resetBoard() {
            flippedCards = [];
            lockBoard = false;
        }
    </script>
</body>
</html>