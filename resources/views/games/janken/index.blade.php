<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            叩いて被ってじゃんけんぽん
        </h2>
    </x-slot>

    <div class="py-6 bg-gray-100 min-h-screen flex flex-col items-center justify-start select-none overflow-hidden relative">
        
        {{-- ■ メッセージ表示エリア ■ --}}
        <div id="message-area" class="w-full text-center h-24 flex items-end justify-center mb-2 z-10">
            <h1 id="main-text" class="text-6xl font-black text-gray-800 drop-shadow-md tracking-widest">
                START
            </h1>
        </div>

        {{-- ■ ゲーム画面エリア ■ --}}
        <div class="relative w-[98%] h-[650px] bg-white border-4 border-black overflow-hidden shadow-2xl rounded-sm">
            
            {{-- 背景画像 --}}
            <img id="bg-img" src="" class="absolute inset-0 w-full h-full object-cover opacity-70 z-0 pointer-events-none">
            <div class="absolute bottom-0 w-full h-16 bg-green-700 z-0 opacity-80"></div> 

            {{-- 制限時間バー --}}
            <div id="timer-bar" class="absolute top-0 left-0 h-4 bg-red-600 z-50 w-full hidden"></div>

            {{-- ■ プレイヤー（左側） ■ --}}
            <div id="player" class="absolute bottom-16 left-[10%] w-64 h-80 border-0 flex items-end justify-center z-10">
                <img id="player-img" src="" class="w-full h-full object-contain drop-shadow-2xl">
                <span class="absolute -bottom-8 text-black font-bold bg-white px-2 rounded border border-black">自分</span>
                
                {{-- 自分の手 --}}
                <div id="player-hand-display" class="absolute -top-40 left-0 w-full text-center text-8xl drop-shadow-md transition-transform duration-100 z-30"></div>

                {{-- ハンマー（通常サイズを少し大きく: w-48 h-48） --}}
                <img id="player-hammer-img" src="" class="hidden absolute -right-32 top-0 w-48 h-48 object-contain transform rotate-12 origin-left z-20 drop-shadow-xl">

                {{-- ★★★ ヘルメット（画像化） ★★★ --}}
                <img id="player-helmet-img" src="" class="hidden absolute -top-24 left-4 w-56 h-56 object-contain z-20 drop-shadow-xl">
            </div>

            {{-- ■ CPU（右側） ■ --}}
            <div id="cpu" class="absolute bottom-16 right-[10%] w-64 h-80 border-0 flex items-end justify-center transition-none z-10">
                <img id="cpu-img" src="" class="w-full h-full object-contain drop-shadow-2xl">
                <span class="absolute -bottom-8 text-black font-bold bg-white px-2 rounded border border-black">相手</span>
                
                {{-- 相手の手 --}}
                <div id="cpu-hand-display" class="absolute -top-40 left-0 w-full text-center text-8xl drop-shadow-md transition-transform duration-100 z-30"></div>

                {{-- ハンマー --}}
                <img id="cpu-hammer-img" src="" class="hidden absolute -left-32 top-0 w-48 h-48 object-contain transform -rotate-12 origin-right z-20 drop-shadow-xl">

                {{-- ★★★ ヘルメット（画像化） ★★★ --}}
                <img id="cpu-helmet-img" src="" class="hidden absolute -top-24 left-4 w-56 h-56 object-contain z-20 drop-shadow-xl">
            </div>
        </div>

        {{-- ■ 操作パネルエリア ■ --}}
        <div class="mt-6 w-full max-w-4xl min-h-[120px] z-10">
            
            {{-- フェーズ1: じゃんけん --}}
            <div id="janken-panel" class="flex justify-center gap-4 mb-4 hidden">
                <button onclick="playJanken(0)" class="w-20 h-20 bg-gray-200 border-b-4 border-gray-400 active:border-b-0 active:mt-1 rounded text-2xl hover:bg-gray-300 flex flex-col items-center justify-center">
                    <span>✊</span><span class="text-xs text-gray-500 mt-1">(1 / Z)</span>
                </button>
                <button onclick="playJanken(1)" class="w-20 h-20 bg-gray-200 border-b-4 border-gray-400 active:border-b-0 active:mt-1 rounded text-2xl hover:bg-gray-300 flex flex-col items-center justify-center">
                    <span>✌️</span><span class="text-xs text-gray-500 mt-1">(2 / X)</span>
                </button>
                <button onclick="playJanken(2)" class="w-20 h-20 bg-gray-200 border-b-4 border-gray-400 active:border-b-0 active:mt-1 rounded text-2xl hover:bg-gray-300 flex flex-col items-center justify-center">
                    <span>🖐</span><span class="text-xs text-gray-500 mt-1">(3 / C)</span>
                </button>
            </div>

            {{-- フェーズ2: アクション --}}
            <div id="action-panel" class="flex justify-between px-10 hidden w-full">
                <div class="flex flex-col items-center w-32">
                    <button id="btn-attack" onclick="doAction('attack')" disabled class="w-32 h-24 bg-red-600 text-white font-black text-xl border-b-8 border-red-800 rounded disabled:bg-gray-400 disabled:border-gray-500 disabled:cursor-not-allowed hover:bg-red-500 active:border-b-0 active:mt-2 transition-all">
                        叩く！<br><span class="text-xs">(← / A)</span>
                    </button>
                </div>
                <div class="flex flex-col items-center w-32">
                    <button id="btn-aiko" onclick="doAction('aiko')" disabled class="w-32 h-24 bg-green-600 text-white font-black text-xl border-b-8 border-green-800 rounded disabled:bg-gray-400 disabled:border-gray-500 disabled:cursor-not-allowed hover:bg-green-500 active:border-b-0 active:mt-2 transition-all">
                        あいこ<br><span class="text-xs">(↓ / S)</span>
                    </button>
                    <button id="btn-reset" onclick="startTutorial()" class="hidden w-40 h-16 bg-blue-500 text-white font-bold rounded shadow-lg hover:bg-blue-600 text-xl border-b-4 border-blue-700 active:border-b-0 active:mt-1">
                        もう一回
                    </button>
                </div>
                <div class="flex flex-col items-center w-32">
                    <button id="btn-defend" onclick="doAction('defend')" disabled class="w-32 h-24 bg-blue-600 text-white font-black text-xl border-b-8 border-blue-800 rounded disabled:bg-gray-400 disabled:border-gray-500 disabled:cursor-not-allowed hover:bg-blue-500 active:border-b-0 active:mt-2 transition-all">
                        被る！<br><span class="text-xs">(→ / D)</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- ■ 煽りオーバーレイ ■ --}}
        <div id="troll-overlay" class="hidden fixed inset-0 bg-black bg-opacity-80 z-[100] flex flex-col items-center justify-center">
            <div class="text-white text-9xl font-black mb-4 animate-bounce">m9(^Д^)</div>
            <p id="troll-text" class="text-white text-3xl font-bold">プギャーｗｗｗ</p>
            <p class="text-gray-300 mt-4">ミス乙ｗｗｗ</p>
        </div>

        {{-- ■ チュートリアル ■ --}}
        <div id="tutorial-overlay" class="fixed inset-0 bg-gray-900 bg-opacity-95 z-[200] flex flex-col items-center justify-center p-8">
            <div class="bg-white p-8 rounded-lg max-w-2xl w-full text-center border-4 border-blue-500 shadow-2xl relative">
                <h3 class="text-2xl font-bold mb-4 border-b-2 border-gray-200 pb-2">
                    チュートリアル (<span id="tutorial-page-num">1</span>/30)
                </h3>
                <p id="tutorial-text" class="text-lg mb-8 min-h-[100px] flex items-center justify-center font-bold px-8"></p>
                <div class="flex justify-between items-center mt-4 px-4">
                    <button id="btn-tutorial-prev" onclick="prevTutorial()" class="text-5xl font-black text-gray-400 hover:text-blue-600 transition-colors p-2 select-none">←</button>
                    <button id="btn-tutorial-next" onclick="nextTutorial()" class="text-5xl font-black text-blue-600 hover:text-blue-800 transition-colors p-2 select-none animate-pulse">→</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ■ JavaScriptロジック ■ --}}
    <script>
        let gameState = 'tutorial'; 
        let myResult = null; 
        let tutorialPage = 0;
        let timerInterval = null;

        // ★★★ 画像枚数設定（環境に合わせて変更してください） ★★★
        const totalCharImages = 10;   
        const totalHammerImages = 10; 
        const totalBgImages = 7;     
        const totalHelmetImages = 8;

        const tutorialTexts = [
            "ようこそ。「叩いて被ってじゃんけんぽん」の世界へ。",
            "これから君には、過酷な試練に挑んでもらう。",
            "ルールはシンプルだ。だが、それゆえに奥が深い。",
            "まず、じゃんけんを行う。",
            "グーはチョキに勝つ。これは基本だ。",
            "チョキはパーに勝つ。これも基本だ。",
            "パーはグーに勝つ。テストに出るぞ。",
            "勝ったら左の「叩く」ボタンを押せ。",
            "負けたら右の「被る」ボタンを押せ。",
            "ここからが重要だ。よく聞け。",
            "あいこの時は、真ん中の「あいこ」ボタンを押せ。",
            "甘えるな。あいこもまた、戦いなのだ。",
            "そして、画面上の文字は全て嘘だ。",
            "「勝った！」と書いてあっても信じるな。",
            "自分の目だけを信じろ。",
            "さらに、制限時間はたったの1秒だ。",
            "1秒以内に判断し、ボタンを押さなければ即死だ。",
            "人間は1秒あれば人生を変えられる。",
            "君ならできるはずだ。",
            "できないなら、君はその程度の存在ということだ。",
            "敵はランダムに行動する。",
            "予測は不可能。反射神経のみが頼りだ。",
            "準備運動は済ませたか？",
            "瞬きは許されない。",
            "呼吸も忘れるな。",
            "指の震えを止めろ。",
            "恐怖に打ち勝て。",
            "さあ、伝説の始まりだ。",
            "幸運を祈る。健闘を祈る。"
        ];

        const els = {
            mainText: document.getElementById('main-text'),
            jankenPanel: document.getElementById('janken-panel'),
            actionPanel: document.getElementById('action-panel'),
            btnAttack: document.getElementById('btn-attack'),
            btnDefend: document.getElementById('btn-defend'),
            btnAiko: document.getElementById('btn-aiko'), 
            btnReset: document.getElementById('btn-reset'),
            player: document.getElementById('player'),
            cpu: document.getElementById('cpu'),
            playerImg: document.getElementById('player-img'),
            cpuImg: document.getElementById('cpu-img'),
            playerHandDisplay: document.getElementById('player-hand-display'),
            cpuHandDisplay: document.getElementById('cpu-hand-display'),
            playerHammerImg: document.getElementById('player-hammer-img'),
            // ヘルメットのIDを画像用のものに変更
            playerHelmetImg: document.getElementById('player-helmet-img'),
            cpuHammerImg: document.getElementById('cpu-hammer-img'),
            cpuHelmetImg: document.getElementById('cpu-helmet-img'), 
            trollOverlay: document.getElementById('troll-overlay'),
            trollText: document.getElementById('troll-text'),
            tutorialOverlay: document.getElementById('tutorial-overlay'),
            tutorialText: document.getElementById('tutorial-text'),
            tutorialPageNum: document.getElementById('tutorial-page-num'),
            btnTutPrev: document.getElementById('btn-tutorial-prev'),
            timerBar: document.getElementById('timer-bar'),
            bgImg: document.getElementById('bg-img'),
        };

        const handEmojis = ["✊", "✌️", "🖐"];

        window.onload = function() {
            setRandomImages();
            startTutorial();
        };
        
        // png -> webp -> jpg -> jpeg の順に全部試す
        function setImage(imgElem, folder, num) {
            // エラー対応をリセット
            imgElem.onerror = null;

            // 1. まず PNG を試す
            imgElem.src = `/images/games/janken/${folder}/${num}.png`;
            
            imgElem.onerror = function() {
                // 2. ダメなら WebP を試す
                this.src = `/images/games/janken/${folder}/${num}.webp`;
                
                this.onerror = function() {
                    // 3. ダメなら JPG (3文字) を試す
                    this.src = `/images/games/janken/${folder}/${num}.jpg`;
                    
                    this.onerror = function() {
                        // 4. ダメなら JPEG (4文字) を試す ★ここが重要！
                        this.src = `/images/games/janken/${folder}/${num}.jpeg`;
                        
                        // これでもダメなら諦める
                        this.onerror = null;
                    }
                }
            };
        }

        function setRandomImages() {
            // キャラ
            const pNum = Math.floor(Math.random() * totalCharImages) + 1;
            const cNum = Math.floor(Math.random() * totalCharImages) + 1;
            setImage(els.playerImg, 'chars', pNum);
            setImage(els.cpuImg, 'chars', cNum);

            // ハンマー
            const phNum = Math.floor(Math.random() * totalHammerImages) + 1;
            const chNum = Math.floor(Math.random() * totalHammerImages) + 1;
            setImage(els.playerHammerImg, 'hammers', phNum);
            setImage(els.cpuHammerImg, 'hammers', chNum);

            // ヘルメット
            const phlmNum = Math.floor(Math.random() * totalHelmetImages) + 1;
            const chlmNum = Math.floor(Math.random() * totalHelmetImages) + 1;
            setImage(els.playerHelmetImg, 'helmets', phlmNum);
            setImage(els.cpuHelmetImg, 'helmets', chlmNum);

            // 背景（背景もこの共通関数を使えばOKです！）
            const bgNum = Math.floor(Math.random() * totalBgImages) + 1;
            setImage(els.bgImg, 'bgs', bgNum);
        }

        function startTutorial() {
            resetGameUI(); 
            gameState = 'tutorial';
            tutorialPage = 0;
            els.tutorialOverlay.classList.remove('hidden');
            updateTutorialUI();
        }

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
            els.btnTutPrev.style.visibility = (tutorialPage === 0) ? 'hidden' : 'visible';
        }

        document.addEventListener('keydown', (e) => {
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
                if (e.key === 'ArrowDown' || e.key === 's' || e.key === 'S') {
                    if (!els.btnAiko.disabled) doAction('aiko');
                }
            }
        });

        function playJanken(playerHand) {
            const cpuHand = Math.floor(Math.random() * 3);
            console.log(`Player: ${playerHand}, CPU: ${cpuHand}`);

            els.playerHandDisplay.innerText = handEmojis[playerHand];
            els.cpuHandDisplay.innerText = handEmojis[cpuHand];

            const resultVal = (playerHand - cpuHand + 3) % 3;
            const randomTexts = ["勝った！", "負けた！", "あいこ！"];
            const lieText = randomTexts[Math.floor(Math.random() * randomTexts.length)];
            
            els.mainText.innerText = lieText;
            els.mainText.className = "text-6xl font-black drop-shadow-md tracking-widest";
            if (Math.random() < 0.5) {
                els.mainText.classList.add('text-red-600');
            } else {
                els.mainText.classList.add('text-blue-600');
            }

            gameState = 'action';
            els.jankenPanel.classList.add('hidden');
            els.actionPanel.classList.remove('hidden');
            els.actionPanel.classList.add('flex');

            if (resultVal === 2) myResult = 'win';
            else if (resultVal === 1) myResult = 'lose';
            else myResult = 'draw';
            
            els.btnAttack.disabled = false;
            els.btnDefend.disabled = false;
            els.btnAiko.disabled = false;
            els.btnAiko.classList.remove('hidden');
            els.btnReset.classList.add('hidden'); 

            startTimer();
        }

        function startTimer() {
            els.timerBar.classList.remove('hidden');
            els.timerBar.style.width = '100%';
            els.timerBar.style.transition = 'none'; 
            void els.timerBar.offsetWidth;
            els.timerBar.style.transition = 'width 1s linear';
            els.timerBar.style.width = '0%';

            if (timerInterval) clearTimeout(timerInterval);
            timerInterval = setTimeout(() => {
                if (gameState === 'action') {
                    showTrollOverlay("時間切れ乙ｗｗｗ");
                }
            }, 1000); 
        }

        function stopTimer() {
            if (timerInterval) clearTimeout(timerInterval);
            els.timerBar.classList.add('hidden');
        }

        function doAction(actionType) {
            if (gameState !== 'action') return;
            
            stopTimer(); 
            gameState = 'result';

            els.btnAttack.disabled = true;
            els.btnDefend.disabled = true;
            els.btnAiko.disabled = true;

            let isSuccess = false;
            if (myResult === 'win' && actionType === 'attack') isSuccess = true;
            else if (myResult === 'lose' && actionType === 'defend') isSuccess = true;
            else if (myResult === 'draw' && actionType === 'aiko') isSuccess = true;

            if (isSuccess) {
                if (actionType === 'attack') {
                    renderResult('hit_success');
                    showResetButton();
                } else if (actionType === 'defend') {
                    renderResult('guard_success');
                    showResetButton();
                } else {
                    els.mainText.innerText = "セーフ！";
                    setTimeout(() => {
                        resetGameUI(); 
                        els.mainText.innerText = "じゃんけん...";
                        gameState = 'janken';
                    }, 500);
                }
            } else {
                showTrollOverlay("ミス乙ｗｗｗ");
            }
        }

        function showResetButton() {
             setTimeout(() => {
                els.btnAiko.classList.add('hidden'); 
                els.btnAttack.classList.add('hidden'); 
                els.btnDefend.classList.add('hidden'); 
                els.btnReset.classList.remove('hidden'); 
            }, 1000);
        }

        function showTrollOverlay(msg) {
            els.trollText.innerText = msg;
            els.trollOverlay.classList.remove('hidden');
            setTimeout(() => {
                els.trollOverlay.classList.add('hidden');
                
                els.btnAiko.classList.add('hidden');
                els.btnAttack.classList.add('hidden'); 
                els.btnDefend.classList.add('hidden'); 
                els.btnReset.classList.remove('hidden');
            }, 3000);
        }

        function renderResult(type) {
            if (type === 'hit_success') {
                els.mainText.innerText = "HIT!!!";
                els.playerHammerImg.classList.remove('hidden');
                
                // ★修正1: 画像を枠いっぱいに無理やり引き伸ばす！
                els.playerHammerImg.style.objectFit = "fill"; 

                // ★修正2: 位置の基準を「自分(left)」からにして、画面右へ突き出す
                els.playerHammerImg.style.width = '2000px'; 
                els.playerHammerImg.style.height = '300px'; 
                els.playerHammerImg.style.transform = 'rotate(0deg)';
                
                // 自分の体の少し右(100px)からスタートさせる
                els.playerHammerImg.style.right = 'auto'; // right指定を解除
                els.playerHammerImg.style.left = '100px'; 
                els.playerHammerImg.style.top = '50px';

                // デバッグの枠線は消す
                els.playerHammerImg.style.border = 'none';

                els.cpu.style.transition = 'none';
                els.cpu.style.right = '-300px'; 
                els.cpu.style.transform = 'rotate(90deg)';

            } else if (type === 'guard_success') {
                els.mainText.innerText = "SAFE!!!";
                els.playerHelmetImg.classList.remove('hidden');
                els.cpuHammerImg.classList.remove('hidden');
                
                // ★修正1: こちらも引き伸ばす
                els.cpuHammerImg.style.objectFit = "fill";

                els.cpuHammerImg.style.width = '2000px';
                els.cpuHammerImg.style.height = '300px';
                els.cpuHammerImg.style.transform = 'rotate(0deg)';
                
                // ★修正2: 相手の体の少し左(-1900px)からスタートして画面左へ突き出す
                // (widthが2000pxあるので、leftを大きくマイナスにする必要がある)
                els.cpuHammerImg.style.left = 'auto'; // left指定を解除
                els.cpuHammerImg.style.right = '100px';
                els.cpuHammerImg.style.top = '50px';
                
                els.cpuHammerImg.style.border = 'none';
                
                els.player.style.transform = 'translateY(10px)';
            }
        }

        function resetGameUI() {
            gameState = 'reset';
            myResult = null;
            stopTimer(); 
            setRandomImages();

            els.mainText.innerText = "START";
            
            els.jankenPanel.classList.remove('hidden'); 
            els.actionPanel.classList.add('hidden');
            els.actionPanel.classList.remove('flex');
            
            els.btnAttack.classList.remove('hidden');
            els.btnDefend.classList.remove('hidden');
            els.btnAiko.classList.remove('hidden');
            els.btnReset.classList.add('hidden');
            
            els.playerHammerImg.classList.add('hidden');
            els.playerHelmetImg.classList.add('hidden');
            els.cpuHammerImg.classList.add('hidden');
            els.cpuHelmetImg.classList.add('hidden');
            
            els.playerHandDisplay.innerText = "";
            els.cpuHandDisplay.innerText = "";
            
            // スタイルリセット
            els.playerHammerImg.style = '';
            els.cpuHammerImg.style = '';
            
            els.cpu.style = '';
            els.player.style = '';
            
            els.btnAttack.disabled = true;
            els.btnDefend.disabled = true;
            els.btnAiko.disabled = true;
        }
    </script>
</x-app-layout>