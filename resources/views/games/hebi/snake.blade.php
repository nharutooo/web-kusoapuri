<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Snake & Ladder Pro</title>

<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f4f1ec;
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.game {
    display: flex;
    gap: 30px;
    align-items: flex-start;
}

/* BOARD CONTAINER */
.board-wrapper {
    position: relative;
    width: 500px;
    height: 500px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    border: 5px solid #333;
}

.board {
    display: grid;
    grid-template-columns: repeat(10, 1fr);
    grid-template-rows: repeat(10, 1fr);
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    z-index: 1; /* Bottom layer */
}

.cell {
    border: 0.5px solid rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: bold;
    color: rgba(0,0,0,0.4);
}

.dark { background: #caa472; }
.light { background: #f0d9b5; }
.path { background: #ffeaa7 !important; color: #000; }

/* SVG OVERLAY */
svg {
    position: absolute;
    top: 0;
    left: 0;
    width: 500px;
    height: 500px;
    z-index: 5; /* Middle layer: above board, below players */
    pointer-events: none; /* Allows clicking through the SVG */
}

.ladder-line {
    stroke: #2ecc71;
    stroke-width: 12;
    stroke-linecap: round;
    opacity: 0.7;
}

.snake-line {
    stroke: #e74c3c;
    stroke-width: 8;
    fill: none;
    stroke-linecap: round;
    opacity: 0.8;
}

/* PLAYERS */
.player {
    position: absolute;
    font-size: 30px;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    z-index: 10; /* Top layer */
    filter: drop-shadow(2px 2px 2px rgba(0,0,0,0.3));
}

/* UI CONTROLS */
.ui {
    width: 220px;
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

button {
    padding: 12px;
    width: 100%;
    font-size: 18px;
    cursor: pointer;
    background: #2c3e50;
    color: white;
    border: none;
    border-radius: 5px;
    transition: background 0.2s;
}

button:hover { background: #34495e; }

#dice {
    font-size: 50px;
    text-align: center;
    margin: 20px 0;
}

.status {
    color: #2c3e50;
    font-size: 1.1rem;
    text-align: center;
    line-height: 1.5;
}

/* Animation */
.rolling { animation: shake 0.5s infinite; }
@keyframes shake {
    0% { transform: translate(0,0) rotate(0); }
    25% { transform: translate(5px, 5px) rotate(10deg); }
    50% { transform: translate(-5px, -5px) rotate(-10deg); }
    100% { transform: translate(0,0) rotate(0); }
}
</style>
</head>

<body>

<div class="game">

    <div class="board-wrapper">
        <svg viewBox="0 0 500 500">
            <line x1="275" y1="475" x2="225" y2="375" class="ladder-line" stroke-dasharray="2,5" />
            <line x1="25" y1="425" x2="25" y2="325" class="ladder-line" />
            <line x1="275" y1="275" x2="475" y2="75" class="ladder-line" />

            <path d="M75 25 Q 150 150, 325 225" class="snake-line" />
            <path d="M225 375 Q 150 450, 75 475" class="snake-line" />
        </svg>

        <div class="board">
            @for ($row = 9; $row >= 0; $row--)
                @php $reverse = $row % 2 === 1; @endphp
                @for ($col = 0; $col < 10; $col++)
                    @php
                        $num = $row * 10 + ($reverse ? (9 - $col) : $col) + 1;
                        $classes = ($num + $row) % 2 === 0 ? 'light' : 'dark';
                    @endphp
                    <div class="cell {{ $classes }}" id="cell-{{ $num }}">
                        {{ $num }}
                    </div>
                @endfor
            @endfor
        </div>

        <div id="player1" class="player">🧑</div>
        <div id="player2" class="player">🧙</div>
    </div>

    <div class="ui">
        <div class="status" id="status">
            <strong>Snake & Ladder</strong><br>
            <span id="turn-text">Player 1's Turn</span>
        </div>
        <div id="dice">🎲</div>
        <button onclick="rollDice()">Roll Dice</button>
    </div>

</div>

<script>
// Logic must match the SVG visual lines
const snakes = {99:54, 25:2};
const ladders = {6:25, 11:40, 46:90};

let currentPlayer = 1;
let positions = {1:1, 2:1};

const players = {
    1: document.getElementById('player1'),
    2: document.getElementById('player2')
};

function movePlayer(playerNum, cellNumber) {
    const cell = document.getElementById('cell-' + cellNumber);
    const board = document.querySelector('.board-wrapper');

    const cellRect = cell.getBoundingClientRect();
    const boardRect = board.getBoundingClientRect();

    // Added minor offset so players don't overlap perfectly
    const offset = playerNum === 1 ? 5 : 15;

    players[playerNum].style.left = (cellRect.left - boardRect.left + offset) + 'px';
    players[playerNum].style.top  = (cellRect.top  - boardRect.top + offset) + 'px';
}

function rollDice() {
    const dice = document.getElementById('dice');
    const btn = document.querySelector('button');
    
    btn.disabled = true;
    dice.classList.add('rolling');

    setTimeout(() => {
        dice.classList.remove('rolling');
        const roll = Math.floor(Math.random() * 6) + 1;
        dice.innerText = getDiceEmoji(roll);

        let start = positions[currentPlayer];
        let target = Math.min(100, start + roll);

        highlightPath(start, target);
        animateMove(currentPlayer, start, target);
    }, 600);
}

function getDiceEmoji(num) {
    const emojis = ['⚀','⚁','⚂','⚃','⚄','⚅'];
    return emojis[num-1];
}

function animateMove(player, from, to) {
    let step = from;
    const interval = setInterval(() => {
        step++;
        positions[player] = step;
        movePlayer(player, step);

        if (step >= to) {
            clearInterval(interval);
            setTimeout(() => handleSpecialSquares(player), 200);
        }
    }, 250);
}

function handleSpecialSquares(player) {
    let pos = positions[player];
    let found = false;

    if (snakes[pos]) {
        positions[player] = snakes[pos];
        found = true;
    } else if (ladders[pos]) {
        positions[player] = ladders[pos];
        found = true;
    }

    if (found) {
        // Animate the "slide" down or "climb" up
        setTimeout(() => {
            movePlayer(player, positions[player]);
            endTurn();
        }, 300);
    } else {
        endTurn();
    }
}

function endTurn() {
    if (positions[currentPlayer] === 100) {
        document.getElementById('status').innerHTML = `<h2 style="color:green">Player ${currentPlayer} Wins!</h2>`;
        return;
    }

    currentPlayer = currentPlayer === 1 ? 2 : 1;
    document.getElementById('turn-text').innerText = `Player ${currentPlayer}'s Turn`;
    document.querySelector('button').disabled = false;
}

function highlightPath(from, to) {
    document.querySelectorAll('.cell').forEach(c => c.classList.remove('path'));
    for (let i = from; i <= to; i++) {
        document.getElementById('cell-' + i)?.classList.add('path');
    }
}

// Initial positioning
window.onload = () => {
    movePlayer(1, 1);
    movePlayer(2, 1);
};
</script>

</body>
</html>