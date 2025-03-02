@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <!-- Judul dan Informasi Juz -->
                <h1 class="text-2xl font-bold mb-6 text-center">Juz {{ $juz['number'] ?? 'Tidak Diketahui' }}</h1>

                @if(isset($error))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ $error }}
                    </div>
                @endif

                <!-- Informasi Surat dalam Juz Ini -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold mb-2">Surat dalam Juz Ini:</h2>
                    <div class="flex flex-wrap gap-2">
                        @php
                            $uniqueSurahs = collect($juz['ayahs'] ?? [])->pluck('surah')->unique('number')->values();
                        @endphp

                        @foreach($uniqueSurahs as $surah)
                            <a href="{{ route('quran.surah', $surah['number']) }}"
                               class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full hover:bg-emerald-200 transition">
                                {{ $surah['name_id'] ?? $surah['name'] ?? 'Tidak Diketahui' }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Daftar Ayat -->
                <div class="space-y-4">
                    @foreach($juz['ayahs'] ?? [] as $index => $ayah)
                        <div class="p-4 border rounded-lg hover:shadow-md transition-shadow">
                            <!-- Ayat dalam Bahasa Arab -->
                            <p class="text-xl font-arabic text-right mb-2 leading-loose">{{ $ayah['text'] ?? 'Tidak Diketahui' }}</p>

                            <!-- Informasi Surah dan Ayat -->
                            <div class="flex justify-between items-center mb-2">
                                <p class="text-sm text-gray-600">
                                    Surah {{ $ayah['surah']['name_id'] ?? $ayah['surah']['name'] ?? 'Tidak Diketahui' }}
                                    Ayat {{ $ayah['numberInSurah'] ?? 'Tidak Diketahui' }}
                                </p>
                                @if(isset($ayah['surah']['number']))
                                    <a href="{{ route('quran.surah', $ayah['surah']['number']) }}" class="text-emerald-600 hover:text-emerald-700 text-sm">
                                        Lihat Surah
                                    </a>
                                @endif
                            </div>

                            <!-- Terjemahan -->
                            @if(isset($ayah['translation']))
                                <div class="mt-2">
                                    <p class="text-sm text-gray-700">
                                        <strong>Terjemahan:</strong> {{ $ayah['translation'] }}
                                    </p>
                                </div>
                            @elseif(isset($translations) && isset($translations[$index]))
                                <div class="mt-2">
                                    <p class="text-sm text-gray-700">
                                        <strong>Terjemahan:</strong> {{ $translations[$index]['text'] }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Navigasi Juz -->
                <div class="mt-8 flex justify-between">
                    @if(($juz['number'] ?? 0) > 1)
                        <a href="{{ route('quran.juz', ($juz['number'] ?? 1) - 1) }}" class="px-4 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700 transition">
                            « Juz Sebelumnya
                        </a>
                    @else
                        <div></div>
                    @endif

                    @if(($juz['number'] ?? 0) < 30)
                        <a href="{{ route('quran.juz', ($juz['number'] ?? 0) + 1) }}" class="px-4 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700 transition">
                            Juz Selanjutnya »
                        </a>
                    @else
                        <div></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
