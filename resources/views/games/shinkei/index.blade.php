<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>真剣衰弱 Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .perspective { perspective: 1000px; }
        .preserve-3d { transform-style: preserve-3d; }
        .backface-hidden { backface-visibility: hidden; }
        .rotate-y-180 { transform: rotateY(180deg); }
        .flipped { transform: rotateY(180deg); }
        .matched-card { opacity: 0.6; transform: rotateY(180deg) scale(0.95); transition: all 0.5s; }
        @keyframes shake {
            0%, 100% { transform: translateX(0) rotateY(180deg); }
            25% { transform: translateX(-5px) rotateY(180deg); }
            75% { transform: translateX(5px) rotateY(180deg); }
        }
        .shake { animation: shake 0.3s; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 to-slate-200 min-h-screen py-8">
    <div class="max-w-xl mx-auto px-4">
        <h1 class="text-4xl font-bold text-center mb-4 text-indigo-700 tracking-tight">真剣衰弱</h1>
        
        <div class="flex justify-around mb-8 bg-white p-4 rounded-2xl shadow-sm border border-slate-200 text-slate-600 font-bold">
            <div class="text-center">
                <span class="block text-xs uppercase tracking-widest opacity-60">Moves</span>
                <span id="move-count" class="text-2xl text-indigo-600">0</span>
            </div>
            <div class="text-center">
                <span class="block text-xs uppercase tracking-widest opacity-60">Matches</span>
                <span id="match-count" class="text-2xl text-green-500">0</span>
            </div>
        </div>
        
        <div class="grid grid-cols-4 gap-3 sm:gap-4" id="game-board">
            @foreach($cards as $index => $emoji)
                <div class="card-container perspective h-28 sm:h-36 cursor-pointer" onclick="flipCard(this)" data-emoji="{{ $emoji }}">
                    <div class="card-inner relative w-full h-full transition-transform duration-500 preserve-3d">
                        <div class="card-back absolute inset-0 bg-indigo-600 rounded-2xl shadow-md flex items-center justify-center text-white text-3xl backface-hidden border-b-4 border-indigo-800">
                            ?
                        </div>
                        <div class="card-front absolute inset-0 bg-white rounded-2xl shadow-md flex items-center justify-center text-4xl sm:text-5xl backface-hidden rotate-y-180 border-2 border-indigo-100">
                            {{ $emoji }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-10 text-center">
            <button onclick="window.location.reload()" class="bg-white text-indigo-600 border-2 border-indigo-600 px-8 py-3 rounded-xl font-bold hover:bg-indigo-600 hover:text-white transition-all duration-300 shadow-sm active:scale-95">
                New Game
            </button>
        </div>
    </div>

    <script>
        let flippedCards = [];
        let lockBoard = false;
        let moves = 0;
        let matches = 0;

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
                    document.getElementById('match-count').innerText = matches;
                    if (matches === 8) {
                        setTimeout(() => alert(`🎉 Win! Moves: ${moves}`), 500);
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

        function resetBoard() {
            flippedCards = [];
            lockBoard = false;
        }
    </script>
</body>
</html>