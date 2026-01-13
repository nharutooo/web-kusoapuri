<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            叩いて被ってじゃんけんぽん
        </h2>
    </x-slot>

    <div class="py-6 bg-gray-100 min-h-screen flex flex-col items-center justify-start select-none overflow-hidden relative">
        
        {{-- ■ メッセージ表示エリア（嘘つき） ■ --}}
        <div id="message-area" class="w-full text-center h-24 flex items-end justify-center mb-2">
            <h1 id="main-text" class="text-6xl font-black text-gray-800 drop-shadow-md tracking-widest">
                START
            </h1>
        </div>

        {{-- ■ ゲーム画面エリア ■ --}}
        <div class="relative w-[98%] h-[500px] bg-white border-4 border-black overflow-hidden shadow-2xl rounded-sm">
            
            {{-- 背景 --}}
            <div class="absolute inset-0 bg-blue-100 opacity-50 pointer-events-none"></div>
            <div class="absolute bottom-0 w-full h-16 bg-green-700"></div> 

            {{-- ■ プレイヤー（左側） ■ --}}
            <div id="player" class="absolute bottom-16 left-[20%] w-24 h-40 bg-blue-500 border-2 border-black flex items-center justify-center">
                <span class="text-white font-bold">自分</span>
                
                {{-- 自分の出した手を表示するエリア --}}
                <div id="player-hand-display" class="absolute -top-24 left-0 w-full text-center text-6xl drop-shadow-md transition-transform duration-100"></div>

                <div id="player-hammer" class="hidden absolute -right-20 top-0 w-24 h-12 bg-red-600 border-2 border-black transform rotate-12 origin-left z-20">
                    <span class="text-xs text-white p-1">ピコハン</span>
                </div>
                <div id="player-helmet" class="hidden absolute -top-8 left-0 w-24 h-10 bg-yellow-400 border-2 border-black rounded-t-full z-20"></div>
            </div>

            {{-- ■ CPU（右側） ■ --}}
            <div id="cpu" class="absolute bottom-16 right-[20%] w-24 h-40 bg-red-500 border-2 border-black flex items-center justify-center transition-none">
                <span class="text-white font-bold">相手</span>
                
                {{-- 相手の出した手を表示するエリア --}}
                <div id="cpu-hand-display" class="absolute -top-24 left-0 w-full text-center text-6xl drop-shadow-md transition-transform duration-100"></div>

                <div id="cpu-hammer" class="hidden absolute -left-20 top-0 w-24 h-12 bg-red-600 border-2 border-black transform -rotate-12 origin-right z-20"></div>
                <div id="cpu-helmet" class="hidden absolute -top-8 left-0 w-24 h-10 bg-yellow-400 border-2 border-black rounded-t-full z-20"></div>
            </div>
        </div>

        {{-- ■ 操作パネルエリア ■ --}}
        <div class="mt-6 w-full max-w-4xl">
            
            {{-- フェーズ1: じゃんけんボタン --}}
            <div id="janken-panel" class="flex justify-center gap-4 mb-4 hidden">
                <button onclick="playJanken(0)" class="w-20 h-20 bg-gray-200 border-b-4 border-gray-400 active:border-b-0 active:mt-1 rounded text-2xl hover:bg-gray-300 flex flex-col items-center justify-center">
                    <span>✊</span>
                    <span class="text-xs text-gray-500 mt-1">(1 / Z)</span>
                </button>
                <button onclick="playJanken(1)" class="w-20 h-20 bg-gray-200 border-b-4 border-gray-400 active:border-b-0 active:mt-1 rounded text-2xl hover:bg-gray-300 flex flex-col items-center justify-center">
                    <span>✌️</span>
                    <span class="text-xs text-gray-500 mt-1">(2 / X)</span>
                </button>
                <button onclick="playJanken(2)" class="w-20 h-20 bg-gray-200 border-b-4 border-gray-400 active:border-b-0 active:mt-1 rounded text-2xl hover:bg-gray-300 flex flex-col items-center justify-center">
                    <span>🖐</span>
                    <span class="text-xs text-gray-500 mt-1">(3 / C)</span>
                </button>
            </div>

            {{-- フェーズ2: アクションボタン --}}
            <div id="action-panel" class="flex justify-between px-20 hidden">
                <div class="flex flex-col items-center">
                    <button id="btn-attack" onclick="doAction('attack')" disabled class="w-32 h-24 bg-red-600 text-white font-black text-xl border-b-8 border-red-800 rounded disabled:bg-gray-400 disabled:border-gray-500 disabled:cursor-not-allowed">
                        叩く！<br><span class="text-xs">(← / A)</span>
                    </button>
                </div>
                <div class="flex flex-col items-center justify-end">
                    <button id="btn-reset" onclick="startTutorial()" class="hidden px-4 py-2 bg-blue-500 text-white rounded shadow hover:bg-blue-600 text-sm">もう一回</button>
                </div>
                <div class="flex flex-col items-center">
                    <button id="btn-defend" onclick="doAction('defend')" disabled class="w-32 h-24 bg-blue-600 text-white font-black text-xl border-b-8 border-blue-800 rounded disabled:bg-gray-400 disabled:border-gray-500 disabled:cursor-not-allowed">
                        被る！<br><span class="text-xs">(→ / D)</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- ■ 煽りオーバーレイ ■ --}}
        <div id="troll-overlay" class="hidden fixed inset-0 bg-black bg-opacity-80 z-[100] flex flex-col items-center justify-center">
            <div class="text-white text-9xl font-black mb-4 animate-bounce">
                m9(^Д^)
            </div>
            <p class="text-white text-3xl font-bold">プギャーｗｗｗ</p>
            <p class="text-gray-300 mt-4">ミス乙ｗｗｗ</p>
        </div>

        {{-- ■ 強制チュートリアルオーバーレイ ■ --}}
        <div id="tutorial-overlay" class="fixed inset-0 bg-gray-900 bg-opacity-95 z-[200] flex flex-col items-center justify-center p-8">
            <div class="bg-white p-8 rounded-lg max-w-2xl w-full text-center border-4 border-blue-500 shadow-2xl relative">
                
                {{-- ページ番号 --}}
                <h3 class="text-2xl font-bold mb-4 border-b-2 border-gray-200 pb-2">
                    チュートリアル (<span id="tutorial-page-num">1</span>/10)
                </h3>
                
                {{-- 本文 --}}
                <p id="tutorial-text" class="text-lg mb-8 min-h-[100px] flex items-center justify-center font-bold px-8">
                    </p>

                {{-- ■■■ 矢印操作エリア ■■■ --}}
                <div class="flex justify-between items-center mt-4 px-4">
                    {{-- 戻る矢印 --}}
                    <button id="btn-tutorial-prev" onclick="prevTutorial()" class="text-5xl font-black text-gray-400 hover:text-blue-600 transition-colors p-2 select-none">
                        ←
                    </button>
                    
                    {{-- 次へ矢印 --}}
                    <button id="btn-tutorial-next" onclick="nextTutorial()" class="text-5xl font-black text-blue-600 hover:text-blue-800 transition-colors p-2 select-none animate-pulse">
                        →
                    </button>
                </div>

            </div>
        </div>

    </div>

    {{-- ■ JavaScriptロジック ■ --}}
    <script>
        let gameState = 'tutorial'; 
        let myResult = null; 
        let tutorialPage = 0;

        const tutorialTexts = [
            "ようこそ。「叩いて被ってじゃんけんぽん」の世界へ。",
            "このゲームは、高度な心理戦と反射神経が要求される。",
            "まず、じゃんけんとは何かを知る必要がある。",
            "グーはチョキに勝ち、チョキはパーに勝つ。",
            "そしてパーはグーに勝つ。これが宇宙の理（ことわり）だ。",
            "勝ったら「叩く」。負けたら「被る」。シンプルだが奥が深い。",
            "画面上の文字は信用するな。嘘つきだ。",
            "自分の頭上と、相手の頭上に出た「手」だけを信じろ。",
            "敵は50%の確率で後出し（イカサマ）をしてくる。",
            "準備はいいか？ 本当にいいか？ では始めよう。"
        ];

        const els = {
            mainText: document.getElementById('main-text'),
            jankenPanel: document.getElementById('janken-panel'),
            actionPanel: document.getElementById('action-panel'),
            btnAttack: document.getElementById('btn-attack'),
            btnDefend: document.getElementById('btn-defend'),
            btnReset: document.getElementById('btn-reset'),
            player: document.getElementById('player'),
            cpu: document.getElementById('cpu'),
            playerHandDisplay: document.getElementById('player-hand-display'),
            cpuHandDisplay: document.getElementById('cpu-hand-display'),
            playerHammer: document.getElementById('player-hammer'),
            playerHelmet: document.getElementById('player-helmet'),
            cpuHammer: document.getElementById('cpu-hammer'),
            cpuHelmet: document.getElementById('cpu-helmet'),
            trollOverlay: document.getElementById('troll-overlay'),
            tutorialOverlay: document.getElementById('tutorial-overlay'),
            tutorialText: document.getElementById('tutorial-text'),
            tutorialPageNum: document.getElementById('tutorial-page-num'),
            btnTutPrev: document.getElementById('btn-tutorial-prev'),
            btnTutNext: document.getElementById('btn-tutorial-next'),
        };

        const handEmojis = ["✊", "✌️", "🖐"];

        window.onload = function() {
            startTutorial();
        };

        function startTutorial() {
            resetGameUI(); 
            gameState = 'tutorial';
            tutorialPage = 0;
            els.tutorialOverlay.classList.remove('hidden');
            updateTutorialUI();
        }

        // ■■■ 戻る処理の追加 ■■■
        function prevTutorial() {
            if (tutorialPage > 0) {
                tutorialPage--;
                updateTutorialUI();
            }
        }

        function nextTutorial() {
            tutorialPage++;
            if (tutorialPage >= tutorialTexts.length) {
                els.tutorialOverlay.classList.add('hidden');
                gameState = 'janken';
                els.jankenPanel.classList.remove('hidden');
                els.mainText.innerText = "じゃんけん...";
            } else {
                updateTutorialUI();
            }
        }

        function updateTutorialUI() {
            els.tutorialText.innerText = tutorialTexts[tutorialPage];
            els.tutorialPageNum.innerText = tutorialPage + 1;

            // 1ページ目は「戻る」を隠す（または無効化っぽく見せる）
            if (tutorialPage === 0) {
                els.btnTutPrev.style.visibility = 'hidden';
            } else {
                els.btnTutPrev.style.visibility = 'visible';
            }
        }

        document.addEventListener('keydown', (e) => {
            
            // ■■■ チュートリアル中のキー操作 (← →) ■■■
            if (gameState === 'tutorial') {
                if (e.key === 'ArrowRight') nextTutorial();
                if (e.key === 'ArrowLeft') prevTutorial();
                return;
            }

            if (gameState === 'janken') {
                if (e.key === '1' || e.key === 'z' || e.key === 'Z') playJanken(0);
                if (e.key === '2' || e.key === 'x' || e.key === 'X') playJanken(1);
                if (e.key === '3' || e.key === 'c' || e.key === 'C') playJanken(2);
            }
            else if (gameState === 'action') {
                if (e.key === 'ArrowLeft' || e.key === 'a' || e.key === 'A') {
                    if (!els.btnAttack.disabled) doAction('attack');
                }
                if (e.key === 'ArrowRight' || e.key === 'd' || e.key === 'D') {
                    if (!els.btnDefend.disabled) doAction('defend');
                }
            }
        });

        function playJanken(playerHand) {
            const isCheating = Math.random() < 0.5;
            let cpuHand;
            if (isCheating) {
                cpuHand = (playerHand + 2) % 3; 
            } else {
                cpuHand = Math.floor(Math.random() * 3);
            }

            console.log(`Cheat: ${isCheating}, Player: ${playerHand}, CPU: ${cpuHand}`);

            els.playerHandDisplay.innerText = handEmojis[playerHand];
            els.cpuHandDisplay.innerText = handEmojis[cpuHand];

            const resultVal = (playerHand - cpuHand + 3) % 3;

            // 嘘つきテキスト（3種類のみ）
            const randomTexts = ["勝った！", "負けた！", "あいこ！"];
            const lieText = randomTexts[Math.floor(Math.random() * randomTexts.length)];
            
            els.mainText.innerText = lieText;
            els.mainText.className = "text-6xl font-black drop-shadow-md tracking-widest";
            if (Math.random() < 0.5) {
                els.mainText.classList.add('text-red-600');
            } else {
                els.mainText.classList.add('text-blue-600');
            }

            if (resultVal === 0) {
                return; 
            }

            gameState = 'action';
            els.jankenPanel.classList.add('hidden');
            els.actionPanel.classList.remove('hidden');
            els.actionPanel.classList.add('flex');

            if (resultVal === 2) {
                myResult = 'win';
            } else {
                myResult = 'lose';
            }
            
            els.btnAttack.disabled = false;
            els.btnDefend.disabled = false;
        }

        function doAction(actionType) {
            if (gameState !== 'action') return;
            gameState = 'result';

            els.btnAttack.disabled = true;
            els.btnDefend.disabled = true;

            let isSuccess = false;
            if (myResult === 'win' && actionType === 'attack') isSuccess = true;
            if (myResult === 'lose' && actionType === 'defend') isSuccess = true;

            if (isSuccess) {
                if (actionType === 'attack') renderResult('hit_success');
                else renderResult('guard_success');
                
                setTimeout(() => {
                    els.btnReset.classList.remove('hidden');
                }, 1000);

            } else {
                showTrollOverlay();
            }
        }

        function showTrollOverlay() {
            els.trollOverlay.classList.remove('hidden');
            setTimeout(() => {
                els.trollOverlay.classList.add('hidden');
                els.mainText.innerText = "残念でしたｗｗｗ";
                els.btnReset.classList.remove('hidden');
            }, 3000);
        }

        function renderResult(type) {
            if (type === 'hit_success') {
                els.mainText.innerText = "HIT!!!";
                els.playerHammer.classList.remove('hidden');
                els.playerHammer.style.width = '1200px'; 
                els.playerHammer.style.transform = 'rotate(0deg)';
                els.playerHammer.style.right = '-1100px'; 
                els.playerHammer.style.top = '20px';

                els.cpu.style.transition = 'none';
                els.cpu.style.right = '-200px'; 
                els.cpu.style.transform = 'rotate(90deg)';
                els.cpu.classList.add('bg-gray-600');

            } else if (type === 'guard_success') {
                els.mainText.innerText = "SAFE!!!";
                els.playerHelmet.classList.remove('hidden');
                els.cpuHammer.classList.remove('hidden');
                els.cpuHammer.style.width = '1200px'; 
                els.cpuHammer.style.transform = 'rotate(0deg)';
                els.cpuHammer.style.left = '-1100px';
                els.player.style.transform = 'translateY(10px)';
            }
        }

        function resetGameUI() {
            gameState = 'reset';
            myResult = null;
            els.mainText.innerText = "START";
            
            els.jankenPanel.classList.add('hidden');
            els.actionPanel.classList.add('hidden');
            els.actionPanel.classList.remove('flex');
            els.btnReset.classList.add('hidden');
            
            els.playerHammer.classList.add('hidden');
            els.playerHelmet.classList.add('hidden');
            els.cpuHammer.classList.add('hidden');
            els.cpuHelmet.classList.add('hidden');
            
            els.playerHandDisplay.innerText = "";
            els.cpuHandDisplay.innerText = "";
            
            els.playerHammer.style = '';
            els.cpuHammer.style = '';
            
            els.cpu.style = '';
            els.cpu.classList.remove('bg-gray-600');
            els.player.style = '';
            els.player.classList.remove('bg-gray-600');
            
            els.btnAttack.disabled = true;
            els.btnDefend.disabled = true;
        }
    </script>
</x-app-layout>