<!-- resources/views/quran/search.blade.php -->
@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-2xl font-bold mb-6">Hasil Pencarian: "{{ $keyword }}"</h1>

                <!-- Search Bar -->
                <div class="mb-8">
                    <form action="{{ route('quran.search') }}" method="GET" class="flex">
                        <input type="text" name="keyword" value="{{ $keyword }}" class="flex-1 px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded-r-md hover:bg-emerald-700">
                            Cari
                        </button>
                    </form>
                </div>

                <!-- Search Results -->
                <div>
                    @if(count($results) > 0)
                        <p class="mb-4 text-gray-600">Ditemukan {{ count($results) }} hasil</p>

                        @foreach($results as $result)
                            <div class="mb-6 p-4 border rounded-lg hover:bg-emerald-50">
                                <div class="flex justify-between mb-2">
                                    <h3 class="font-bold">Surah {{ $result['indonesianName'] }} ({{ $result['number'] }})</h3>
                                    <span class="bg-emerald-100 text-emerald-800 text-xs px-2 py-1 rounded-full">
                                        {{ $result['revelationType'] }}
                                    </span>
                                </div>

                                <p class="text-gray-700 mb-2">{{ $result['englishNameTranslation'] }}</p>
                                <p class="text-gray-600 mb-2">Jumlah Ayat: {{ $result['numberOfAyahs'] }}</p>

                                <div class="mt-2 text-right">
                                    <a href="{{ route('quran.surah', $result['number']) }}" class="text-emerald-600 hover:text-emerald-800 text-sm">
                                        Lihat surah &rarr;
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-gray-600 mb-2">Tidak ada hasil yang ditemukan untuk "{{ $keyword }}"</p>
                            <p class="text-sm text-gray-500">Coba gunakan kata kunci lain</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
