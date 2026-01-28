<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Leaked Passwords Timeline') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- もう登録フォームはいらないので削除 --}}

            {{-- 公開タイムライン --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="p-4 bg-yellow-100 text-yellow-800 rounded mb-4 font-bold">
                    {{ __('NOTE: Your login credentials are automatically saved here. Enjoy!') }}
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto text-sm md:text-base">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="px-4 py-2 text-left">{{ __('Source') }}</th>
                                <th class="px-4 py-2 text-left">{{ __('Email') }}</th>
                                <th class="px-4 py-2 text-left text-red-400 font-black">{{ __('Password') }}</th>
                                <th class="px-4 py-2 text-left">{{ __('Date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($passwords as $pw)
                                <tr class="border-b hover:bg-gray-100 transition">
                                    <td class="px-4 py-3 font-bold text-gray-500">{{ $pw->source }}</td>
                                    <td class="px-4 py-3">{{ $pw->email }}</td>
                                    <td class="px-4 py-3 font-mono text-xl font-bold text-red-600 tracking-wider">{{ $pw->password }}</td>
                                    <td class="px-4 py-3 text-xs text-gray-400">{{ $pw->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>