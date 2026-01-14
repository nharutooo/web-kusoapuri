<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>神経衰弱</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .perspective { perspective: 1000px; }
        .preserve-3d { transform-style: preserve-3d; }
        .backface-hidden { backface-visibility: hidden; }
        .rotate-y-180 { transform: rotateY(180deg); }
        .flipped { transform: rotateY(180deg); }
        .matched-card { opacity: 0.5; transform: rotateY(180deg) scale(0.9); transition: all 0.5s; pointer-events: none; }
        @keyframes shake {
            0%, 100% { transform: translateX(0) rotateY(180deg); }
            25% { transform: translateX(-5px) rotateY(180deg); }
            75% { transform: translateX(5px) rotateY(180deg); }
        }
        .shake { animation: shake 0.3s; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 to-slate-200 min-h-screen py-8">

    <div id="win-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-500">
        <div class="bg-white p-8 rounded-3xl shadow-2xl text-center max-w-sm mx-4 transform scale-90 transition-transform duration-500" id="win-modal">
            <div class="text-6xl mb-4">🏆</div>
            <h2 class="text-3xl font-bold text-slate-800 mb-2">クリア！</h2>
            <p class="text-slate-600 mb-6">素晴らしい記憶力です！</p>
            <div class="bg-indigo-50 rounded-2xl p-4 mb-8 text-center">
                <p class="text-sm uppercase tracking-widest text-indigo-400 font-bold">Total Moves</p>
                <p id="final-moves" class="text-4xl font-black text-indigo-600">0</p>
            </div>
            <button onclick="window.location.reload()" class="w-full bg-indigo-600 text-white py-4 rounded-xl font-bold text-lg hover:bg-indigo-700 shadow-lg active:scale-95 transition">
                もう一度遊ぶ
            </button>
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-4">
        <h1 class="text-4xl font-bold text-center mb-6 text-indigo-700 tracking-tight">神経衰弱</h1>
        
        <div class="flex justify-center gap-2 mb-8">
            @foreach([1, 2, 3, 4] as $l)
                <a href="?level={{ $l }}" 
                   class="px-4 py-2 rounded-xl font-bold transition-all duration-200 {{ $level == $l ? 'bg-indigo-600 text-white shadow-lg scale-110' : 'bg-white text-indigo-400 border border-slate-200 hover:border-indigo-300' }}">
                    Lv.{{ $l }}
                </a>
            @endforeach
        </div>

        <div class="flex justify-around mb-8 bg-white p-4 rounded-2xl shadow-sm border border-slate-200 text-slate-600 font-bold uppercase tracking-widest text-xs">
            <div class="text-center">
                <span>Moves:</span>
                <span id="move-count" class="text-xl text-indigo-600 ml-1">0</span>
            </div>
            <div class="text-center">
                <span>Matches:</span>
                <span id="match-count" class="text-xl text-green-500 ml-1">0 / {{ $pairCount }}</span>
            </div>
        </div>
        
        <div class="grid {{ $level == 1 ? 'grid-cols-2' : ($level == 4 ? 'grid-cols-5' : 'grid-cols-4') }} gap-3" id="game-board">
            @foreach($cards as $index => $emoji)
                <div class="card-container perspective h-24 sm:h-32 cursor-pointer" onclick="flipCard(this)" data-emoji="{{ $emoji }}">
                    <div class="card-inner relative w-full h-full transition-transform duration-500 preserve-3d">
                        <div class="card-back absolute inset-0 bg-indigo-500 rounded-xl shadow-md flex items-center justify-center text-white text-3xl backface-hidden border-b-4 border-indigo-700">
                            ?
                        </div>
                        <div class="card-front absolute inset-0 bg-white rounded-xl shadow-md flex items-center justify-center text-3xl sm:text-4xl backface-hidden rotate-y-180 border-2 border-indigo-100">
                            {{ $emoji }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-12 flex justify-center">
            <button onclick="window.location.reload()" class="flex items-center gap-2 bg-white text-slate-500 border border-slate-200 px-6 py-2 rounded-full hover:bg-slate-50 transition shadow-sm font-bold">
                🔄 リスタート
            </button>
        </div>
    </div>

    <script>
        let flippedCards = [];
        let lockBoard = false;
        let moves = 0;
        let matches = 0;
        const totalPairs = {{ $pairCount }};

        function flipCard(card) {
            const inner = card.querySelector('.card-inner');
            if (lockBoard || inner.classList.contains('flipped') || card.classList.contains('matched')) return;

            inner.classList.add('flipped');
            flippedCards.push(card);

            if (flippedCards.length === 2) {
                moves++;
                document.getElementById('move-count').innerText = moves;
                checkMatch();
            }
        }

        function checkMatch() {
            lockBoard = true;
            const isMatch = flippedCards[0].dataset.emoji === flippedCards[1].dataset.emoji;

            if (isMatch) {
                setTimeout(() => {
                    flippedCards.forEach(card => {
                        card.classList.add('matched');
                        card.querySelector('.card-inner').classList.add('matched-card');
                    });
                    matches++;
                    document.getElementById('match-count').innerText = `${matches} / ${totalPairs}`;
                    if (matches === totalPairs) {
                        showWinScreen();
                    }
                    resetBoard();
                }, 400);
            } else {
                flippedCards.forEach(card => card.querySelector('.card-inner').classList.add('shake'));
                setTimeout(() => {
                    flippedCards.forEach(card => {
                        card.querySelector('.card-inner').classList.remove('flipped', 'shake');
                    });
                    resetBoard();
                }, 1000);
            }
        }

        function showWinScreen() {
            // Confetti
            const end = Date.now() + 3000;
            (function frame() {
                confetti({ particleCount: 3, angle: 60, spread: 55, origin: { x: 0 }, colors: ['#6366f1', '#ec4899'] });
                confetti({ particleCount: 3, angle: 120, spread: 55, origin: { x: 1 }, colors: ['#6366f1', '#ec4899'] });
                if (Date.now() < end) requestAnimationFrame(frame);
            }());

            // Show Modal
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