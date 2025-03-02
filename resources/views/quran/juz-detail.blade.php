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

                <!-- Daftar Ayat -->
                <div class="space-y-4">
                    @foreach($juz['ayahs'] as $ayah)
                        <div class="p-4 border rounded-lg hover:shadow-md transition-shadow">
                            <!-- Ayat dalam Bahasa Arab -->
                            <p class="text-xl font-arabic text-right mb-2">{{ $ayah['text'] ?? 'Tidak Diketahui' }}</p>

                            <!-- Informasi Surah dan Ayat -->
                            <div class="flex justify-between items-center mb-2">
                                <p class="text-sm text-gray-600">
                                    Surah {{ $ayah['surah']['name_id'] ?? 'Tidak Diketahui' }} Ayat {{ $ayah['numberInSurah'] ?? 'Tidak Diketahui' }}
                                </p>
                                @if(isset($ayah['surah']['number']))
                                    <a href="{{ route('quran.surah', $ayah['surah']['number']) }}" class="text-emerald-600 hover:text-emerald-700 text-sm">
                                        Lihat Surah
                                    </a>
                                @endif
                            </div>

                            <!-- Terjemahan -->
                            @if(isset($translations) && isset($translations[$ayah['numberInSurah'] - 1]))
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600">
                                        <strong>Terjemahan:</strong> {{ $translations[$ayah['numberInSurah'] - 1]['text'] }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
