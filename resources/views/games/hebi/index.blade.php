<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Snake & Ladder - Pixel Perfect Edition</title>

<style>
body { font-family: 'Segoe UI', sans-serif; background: #e9e4d4; display: flex; justify-content: center; margin-top: 20px; }
.game { display: flex; gap: 30px; align-items: flex-start; }

/* BOARD STYLE */
.board-wrapper {
    position: relative;
    width: 500px;
    height: 500px;
    background: #d7c0a3;
    border: 10px solid #4e342e;
    box-shadow: 0 20px 40px rgba(0,0,0,0.4);
    box-sizing: border-box; /* Ensures border doesn't add to width */
}

.board {
    display: grid;
    grid-template-columns: repeat(10, 1fr);
    grid-template-rows: repeat(10, 1fr);
    width: 100%; height: 100%;
    position: absolute; z-index: 1;
}

.cell { 
    border: 1px solid rgba(0,0,0,0.1); 
    box-sizing: border-box;
    display: flex; 
    flex-wrap: wrap; /* Allows players to wrap inside the box */
    align-content: flex-start; 
    justify-content: center; 
    font-size: 10px; 
    font-weight: bold; 
    color: rgba(78, 52, 46, 0.5); 
    position: relative; 
    padding-top: 12px; /* Moves the square number up to make room */
}

.dark { background: #c8ad8d; }
.light { background: #dfcfb8; }
.path { background: #fbc02d !important; }

/* VISUAL SNAKES AND LADDERS */
svg { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 5; pointer-events: none; }
.snake-body { fill: none; stroke-width: 16; stroke-linecap: round; filter: drop-shadow(4px 4px 4px rgba(0,0,0,0.4)); }
.snake-scales { fill: none; stroke: rgba(255,255,255,0.2); stroke-width: 16; stroke-dasharray: 4, 8; stroke-linecap: round; }
.ladder-rail { stroke: #2e7d32; stroke-width: 8; stroke-linecap: round; filter: drop-shadow(3px 3px 2px rgba(0,0,0,0.3)); }
.ladder-rung { stroke: #1b5e20; stroke-width: 5; stroke-dasharray: 2, 12; }

/* PLAYERS - TIGHTLY CONSTRAINED */
.player {
    position: absolute; 
    font-size: 16px; /* Slightly smaller to guarantee fit */
    width: 22px; 
    height: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.4s ease-in-out;
    z-index: 10; 
    filter: drop-shadow(1px 1px 1px rgba(0,0,0,0.3));
    display: none; 
}

/* UI Panel */
.ui { width: 220px; background: white; padding: 25px; border-radius: 15px; box-shadow: 0 8px 20px rgba(0,0,0,0.15); text-align: center; }
.controls-group { margin-bottom: 15px; text-align: left; }
select { width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc; }
button { padding: 14px; width: 100%; font-size: 16px; cursor: pointer; border: none; border-radius: 8px; font-weight: bold; margin-top: 12px; }
.btn-roll { background: #d84315; color: white; }
.btn-reset { background: #78909c; color: white; font-size: 14px; }
#dice { font-size: 60px; margin: 10px 0; }
.status { color: #2c3e50; font-weight: bold; min-height: 80px; display: flex; align-items: center; justify-content: center; }

.rolling { animation: shake 0.5s infinite; }
@keyframes shake { 0% { transform: rotate(0); } 25% { transform: rotate(15deg); } 50% { transform: rotate(-15deg); } 100% { transform: rotate(0); } }
</style>
</head>

<body>

<div class="game">
    <div class="board-wrapper">
        <svg viewBox="0 0 500 500">
            <g id="ladders">
                <line x1="125" y1="475" x2="225" y2="275" class="ladder-rail" /> <line x1="125" y1="475" x2="225" y2="275" class="ladder-rung" />
                <line x1="375" y1="475" x2="275" y2="375" class="ladder-rail" /> <line x1="375" y1="475" x2="275" y2="375" class="ladder-rung" />
                <line x1="475" y1="375" x2="375" y2="125" class="ladder-rail" /> <line x1="475" y1="375" x2="375" y2="125" class="ladder-rung" />
                <line x1="75" y1="225" x2="25" y2="125" class="ladder-rail" /> <line x1="75" y1="225" x2="25" y2="125" class="ladder-rung" />
                <line x1="125" y1="75" x2="175" y2="25" class="ladder-rail" /> <line x1="125" y1="75" x2="175" y2="25" class="ladder-rung" />
            </g>

            <g id="snakes">
                <path d="M175 225 Q 100 350, 75 475" class="snake-body" stroke="#c62828" /> <path d="M175 225 Q 100 350, 75 475" class="snake-scales" />
                <circle cx="175" cy="225" r="9" fill="#c62828" /> <circle cx="172" cy="223" r="2" fill="white" />

                <path d="M125 125 Q 200 225, 225 425" class="snake-body" stroke="#b71c1c" /> <path d="M125 125 Q 200 225, 225 425" class="snake-scales" />
                <circle cx="125" cy="125" r="9" fill="#b71c1c" /> <circle cx="122" cy="123" r="2" fill="white" />

                <path d="M325 225 Q 400 300, 325 375" class="snake-body" stroke="#d32f2f" /> <path d="M325 225 Q 400 300, 325 375" class="snake-scales" />
                <circle cx="325" cy="225" r="9" fill="#d32f2f" /> <circle cx="322" cy="223" r="2" fill="white" />

                <path d="M425 75 Q 475 125, 175 175" class="snake-body" stroke="#c62828" /> <path d="M425 75 Q 475 125, 175 175" class="snake-scales" />
                <circle cx="425" cy="75" r="9" fill="#c62828" /> <circle cx="422" cy="73" r="2" fill="white" />

                <path d="M75 25 Q 150 200, 225 375" class="snake-body" stroke="#b71c1c" /> <path d="M75 25 Q 150 200, 225 375" class="snake-scales" />
                <circle cx="75" cy="25" r="9" fill="#b71c1c" /> <circle cx="72" cy="23" r="2" fill="white" />

                <path d="M275 25 Q 350 75, 325 125" class="snake-body" stroke="#d32f2f" /> <path d="M275 25 Q 350 75, 325 125" class="snake-scales" />
                <circle cx="275" cy="25" r="9" fill="#d32f2f" /> <circle cx="272" cy="23" r="2" fill="white" />
            </g>
        </svg>

        <div class="board">
            @for ($row = 9; $row >= 0; $row--)
                @php $reverse = $row % 2 === 1; @endphp
                @for ($col = 0; $col < 10; $col++)
                    @php
                        $num = $row * 10 + ($reverse ? (9 - $col) : $col) + 1;
                        $classes = ($num + $row) % 2 === 0 ? 'light' : 'dark';
                    @endphp
                    <div class="cell {{ $classes }}" id="cell-{{ $num }}">{{ $num }}</div>
                @endfor
            @endfor
        </div>

        <div id="p1" class="player">🧑</div>
        <div id="p2" class="player">🧙</div>
        <div id="p3" class="player">🧛</div>
        <div id="p4" class="player">🤖</div>
    </div>

    <div class="ui">
        <div class="controls-group">
            <label><strong>Players:</strong></label>
            <select id="playerCount" onchange="resetGame()">
                <option value="2">2 Players</option>
                <option value="3">3 Players</option>
                <option value="4">4 Players</option>
            </select>
        </div>
        <div class="status" id="status">Ready!</div>
        <div id="dice">🎲</div>
        <button id="rollBtn" class="btn-roll" onclick="rollDice()">Roll Dice</button>
        <button class="btn-reset" onclick="resetGame()">🔄 Restart</button>
    </div>
</div>

<script>
const snakes = {57: 2, 78: 16, 54: 27, 89: 64, 99: 25, 95: 74};
const ladders = {3: 45, 8: 26, 30: 73, 59: 80, 83: 97};
const playerEmojis = {1: '🧑', 2: '🧙', 3: '🧛', 4: '🤖'};

let totalPlayers = 2;
let currentPlayer = 1;
let positions = {1:1, 2:1, 3:1, 4:1};
let isGameOver = false;

function movePlayer(pNum, cellNumber) {
    const cell = document.getElementById('cell-' + cellNumber);
    const playerEl = document.getElementById('p' + pNum);
    
    // STRICT LOCAL OFFSETTING
    // Cell is roughly 48x48 (50 minus borders). Emojis are 16px.
    const offsets = { 
        1: [2, 18],  // Middle-ish Left
        2: [24, 18], // Middle-ish Right
        3: [2, 30],  // Bottom Left
        4: [24, 30]  // Bottom Right
    };
    
    const [offX, offY] = offsets[pNum];
    
    // Use offsetLeft/Top which are relative to the parent board-wrapper
    playerEl.style.left = (cell.offsetLeft + offX) + 'px';
    playerEl.style.top  = (cell.offsetTop + offY) + 'px';
}

function resetGame() {
    totalPlayers = parseInt(document.getElementById('playerCount').value);
    currentPlayer = 1; isGameOver = false; positions = {1:1, 2:1, 3:1, 4:1};

    for(let i=1; i<=4; i++) {
        const p = document.getElementById('p' + i);
        if (i <= totalPlayers) {
            p.style.display = 'flex';
            movePlayer(i, 1);
        } else {
            p.style.display = 'none';
        }
    }
    document.getElementById('status').innerHTML = `👉 Player 1's turn`;
    document.getElementById('rollBtn').disabled = false;
    document.querySelectorAll('.cell').forEach(c => c.classList.remove('path'));
}

function rollDice() {
    if(isGameOver) return;
    const btn = document.getElementById('rollBtn');
    btn.disabled = true;
    document.getElementById('dice').classList.add('rolling');

    setTimeout(() => {
        document.getElementById('dice').classList.remove('rolling');
        const roll = Math.floor(Math.random() * 6) + 1;
        document.getElementById('dice').innerText = ['⚀','⚁','⚂','⚃','⚄','⚅'][roll-1];

        let start = positions[currentPlayer];
        let target = start + roll;
        if (target > 100) target = 100 - (target - 100);

        highlightPath(start, target);
        animateMove(currentPlayer, start, target);
    }, 600);
}

function animateMove(pNum, from, to) {
    let step = from;
    const interval = setInterval(() => {
        step < to ? step++ : step--;
        positions[pNum] = step;
        movePlayer(pNum, step);
        if (step === to) {
            clearInterval(interval);
            setTimeout(() => handleSpecial(pNum), 200);
        }
    }, 150);
}

function handleSpecial(pNum) {
    let pos = positions[pNum];
    if (snakes[pos]) {
        positions[pNum] = snakes[pos];
        document.getElementById('status').innerHTML = "🐍 Snake!";
        movePlayer(pNum, positions[pNum]);
    } else if (ladders[pos]) {
        positions[pNum] = ladders[pos];
        document.getElementById('status').innerHTML = "🪜 Ladder!";
        movePlayer(pNum, positions[pNum]);
    }
    
    if (positions[pNum] === 100) {
        document.getElementById('status').innerHTML = "🏆 Winner!";
        isGameOver = true;
    } else {
        setTimeout(() => {
            currentPlayer = (currentPlayer % totalPlayers) + 1;
            document.getElementById('status').innerHTML = `👉 Player ${currentPlayer}'s turn`;
            document.getElementById('rollBtn').disabled = false;
        }, 400);
    }
}

function highlightPath(from, to) {
    document.querySelectorAll('.cell').forEach(c => c.classList.remove('path'));
    const low = Math.min(from, to);
    const high = Math.max(from, to);
    for (let i = low; i <= high; i++) document.getElementById('cell-' + i)?.classList.add('path');
}

window.onload = resetGame;
</script>
</body>
</html>