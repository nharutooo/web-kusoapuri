<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            早押しゲーム
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-slate-50  dark:from-gray-900 dark:via-gray-950 dark:to-gray-900">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/95 dark:bg-gray-900/85 backdrop-blur shadow-lg sm:rounded-2xl border border-white/70 dark:border-gray-800">
                <div class="p-10 sm:p-12 text-center space-y-10">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <a
                            href="{{ route('games.hayaoshi.tutorial') }}"
                            class="w-full h-full mx-auto min-h-44 sm:min-h-48 rounded-3xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 px-8 py-12 shadow-sm hover:shadow-lg transition hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 flex flex-col items-center justify-center text-center"
                        >
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">チュートリアル</p>
                        </a>

                        <a
                            href="{{ route('games.hayaoshi.main') }}"
                            class="w-full h-full mx-auto min-h-44 sm:min-h-48 rounded-3xl border border-gray-300 dark:border-gray-700 bg-gray-900 text-white px-8 py-12 shadow-sm hover:shadow-xl transition hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 flex flex-col items-center justify-center text-center"
                        >
                            <p class="text-2xl font-bold">本番</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
