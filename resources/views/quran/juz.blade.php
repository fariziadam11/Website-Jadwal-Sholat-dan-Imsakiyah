@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-2xl font-bold mb-6 text-center">Daftar Juz Al-Quran</h1>

                @if(isset($error))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ $error }}
                    </div>
                @endif

                <div class="mb-6 border-b border-gray-200">
                    <div class="flex">
                        <a href="{{ route('quran.index') }}" class="px-4 py-2 border-b-2 {{ request()->is('quran') ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm">
                            Surah
                        </a>
                        <a href="{{ route('quran.juz') }}" class="px-4 py-2 border-b-2 {{ request()->is('quran/juz') ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} font-medium text-sm">
                            Juz
                        </a>
                    </div>
                </div>

                <!-- Daftar Juz -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($juzs as $juz)
                        <a href="{{ route('quran.juz.detail', $juz['number']) }}" class="block p-4 border rounded-lg hover:bg-emerald-50 transition">
                            <div class="flex items-center">
                                <div class="w-10 h-10 flex items-center justify-center bg-emerald-100 rounded-full text-emerald-600 font-bold mr-3">
                                    {{ $juz['number'] }}
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900">Juz {{ $juz['number'] }}</h3>
                                    <p class="text-sm text-gray-600">{{ $juz['start'] }} - {{ $juz['end'] }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
