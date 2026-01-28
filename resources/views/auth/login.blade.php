<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- ★★★ 追加：パスワード表示切り替えチェックボックス ★★★ --}}
        <div class="mt-2">
            <label for="show_password" class="inline-flex items-center">
                <input id="show_password" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" onclick="togglePassword()">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Show Password') }}</span>
            </label>
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    {{-- ★★★ あべこべキーボード（パスワード表示機能付き） ★★★ --}}
    <script>
        // パスワード表示切り替え関数
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input[type="email"], input[type="password"]');
            
            // 完全ランダムなマッピングリスト
            const charMap = {
                // 小文字 -> 記号や数字、大文字へ
                'a': '7', 'b': 'Q', 'c': '#', 'd': '9', 'e': '?', 'f': 'L', 'g': '1',
                'h': '@', 'i': 'x', 'j': '!', 'k': '0', 'l': '$', 'm': 'R', 'n': '%',
                'o': '3', 'p': '&', 'q': 'M', 'r': '8', 's': 'P', 't': '5', 'u': 'W',
                'v': '^', 'w': '2', 'x': 'Z', 'y': '4', 'z': 'A',
                
                // 大文字 -> 小文字や別の記号へ
                'A': 'y', 'B': 'n', 'C': 'k', 'D': 'm', 'E': 'o', 'F': 'p', 'G': 'q',
                'H': 'r', 'I': 's', 'J': 't', 'K': 'u', 'L': 'v', 'M': 'w', 'N': 'a',
                'O': 'b', 'P': 'c', 'Q': 'd', 'R': 'e', 'S': 'f', 'T': 'g', 'U': 'h',
                'V': 'i', 'W': 'j', 'X': 'l', 'Y': 'z', 'Z': 'x',

                // 数字 -> 記号や文字へ
                '0': 'K', '1': 'J', '2': 'H', '3': 'G', '4': 'F', 
                '5': 'D', '6': 'S', '7': 'A', '8': '<', '9': '>',

                // 記号 -> 数字や文字へ
                '@': '1', '.': 'X', '-': '+', '_': '=', '!': '?', '?': '!',
                '#': '3', '$': '4', '%': '5', '&': '7', '*': '8', '+': '-',
                '=': '_', '<': ',', '>': '.', ',': '<', '/': '\\'
            };
    
            inputs.forEach(input => {
                input.addEventListener('keydown', function(e) {
                    // 制御キーは許可
                    if (e.key.length > 1 || e.ctrlKey || e.metaKey || e.altKey) return;
    
                    e.preventDefault(); 
    
                    const originalChar = e.key;
                    let newChar = charMap[originalChar] || originalChar;
                    
                    // 10%の確率で完全なノイズ
                    if (Math.random() < 0.1) {
                        const noiseChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()';
                        newChar = noiseChars.charAt(Math.floor(Math.random() * noiseChars.length));
                    }
    
                    const start = this.selectionStart;
                    const end = this.selectionEnd;
                    const val = this.value;
                    this.value = val.substring(0, start) + newChar + val.substring(end);
                    
                    this.selectionStart = this.selectionEnd = start + 1;
                });
            });
        });
    </script>
</x-guest-layout>