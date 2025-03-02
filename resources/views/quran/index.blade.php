@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h1 class="text-2xl font-bold mb-6 text-center">Daftar Surah dan Juz Al-Quran</h1>

                <!-- Search Bar -->
                <div class="mb-6">
                    <form action="{{ route('quran.search') }}" method="GET" class="flex">
                        <input type="text" name="keyword" placeholder="Cari Surah..." class="flex-1 px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <button type="submit" class="bg-emerald-600 text-white px-4 py-2 rounded-r-md hover:bg-emerald-700">
                            Cari
                        </button>
                    </form>
                </div>

                <!-- Navigation Tabs -->
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

                @if(isset($error))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ $error }}
                    </div>
                @endif

                <!-- Konten Surah atau Juz -->
                @if(request()->is('quran'))
                    <!-- Daftar Surah -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($surahs as $surah)
                            <div class="p-4 border rounded-lg hover:bg-emerald-50 transition group">
                                <a href="{{ route('quran.surah', $surah['number']) }}" class="block">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 flex items-center justify-center bg-emerald-100 rounded-full text-emerald-600 font-bold mr-3">
                                            {{ $surah['number'] }}
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-gray-900">{{ $surah['indonesianName'] }}</h3>
                                            <p class="text-sm text-gray-600">{{ $surah['englishName'] }} • {{ $surah['numberOfAyahs'] }} Ayat</p>
                                        </div>
                                        <div class="ml-auto text-right">
                                            <p class="text-xl font-arabic text-gray-800">{{ $surah['name'] }}</p>
                                        </div>
                                    </div>
                                </a>
                                <!-- Audio Controls -->
                                <div class="mt-3 pt-2 border-t border-gray-100 opacity-0 group-hover:opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity">
                                    <audio id="audio-surah-{{ $surah['number'] }}" class="w-full h-10 sm:h-8" controls>
                                        <source src="https://cdn.islamic.network/quran/audio-surah/128/ar.alafasy/{{ $surah['number'] }}.mp3" type="audio/mp3">
                                        Browser Anda tidak mendukung tag audio.
                                    </audio>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @elseif(request()->is('quran/juz'))
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
                @elseif(request()->is('quran/audio'))
                    <!-- Daftar Audio Qari -->
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold mb-4">Pilih Qari</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="p-4 border rounded-lg hover:bg-emerald-50 transition cursor-pointer" onclick="setQari('ar.alafasy')">
                                <h3 class="font-bold">Mishary Rashid Alafasy</h3>
                                <p class="text-sm text-gray-600">Qari terkenal dari Kuwait</p>
                            </div>
                            <div class="p-4 border rounded-lg hover:bg-emerald-50 transition cursor-pointer" onclick="setQari('ar.abdurrahmaansudais')">
                                <h3 class="font-bold">Abdurrahman As-Sudais</h3>
                                <p class="text-sm text-gray-600">Imam Masjidil Haram</p>
                            </div>
                            <div class="p-4 border rounded-lg hover:bg-emerald-50 transition cursor-pointer" onclick="setQari('ar.ahmedajamy')">
                                <h3 class="font-bold">Ahmed ibn Ali al-Ajamy</h3>
                                <p class="text-sm text-gray-600">Qari dari Arab Saudi</p>
                            </div>
                        </div>
                    </div>

                    <!-- Daftar Surah Audio -->
                    <div id="audio-surah-list" class="mt-8">
                        <h2 class="text-xl font-semibold mb-4">Daftar Surah Audio</h2>
                        <div class="space-y-3">
                            @foreach($surahs as $surah)
                                <div class="p-4 border rounded-lg hover:bg-emerald-50 transition">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 flex items-center justify-center bg-emerald-100 rounded-full text-emerald-600 font-bold mr-3">
                                                {{ $surah['number'] }}
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-gray-900">{{ $surah['indonesianName'] }}</h3>
                                                <p class="text-sm text-gray-600">{{ $surah['englishName'] }} • {{ $surah['numberOfAyahs'] }} Ayat</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-xl font-arabic text-gray-800">{{ $surah['name'] }}</p>
                                        </div>
                                    </div>

                                    <div class="mt-3 pt-2 border-t border-gray-100">
                                        <audio id="audio-full-surah-{{ $surah['number'] }}" class="w-full" controls
                                               data-surah="{{ $surah['number'] }}">
                                            <source src="https://cdn.islamic.network/quran/audio-surah/128/ar.alafasy/{{ $surah['number'] }}.mp3" type="audio/mp3">
                                            Browser Anda tidak mendukung tag audio.
                                        </audio>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if(request()->is('quran/audio'))
<script>
    // Default qari
    let currentQari = 'ar.alafasy';

    function setQari(qari) {
        currentQari = qari;

        // Update all audio sources
        const audioElements = document.querySelectorAll('audio');
        audioElements.forEach(audio => {
            const surahNumber = audio.dataset.surah;
            if (surahNumber) {
                const newSource = `https://cdn.islamic.network/quran/audio-surah/128/${qari}/${surahNumber}.mp3`;
                audio.querySelector('source').src = newSource;
                audio.load(); // Reload the audio with new source
            }
        });

        // Show notification
        const notification = document.createElement('div');
        notification.className = 'fixed bottom-4 right-4 bg-emerald-500 text-white px-4 py-2 rounded shadow';
        notification.textContent = 'Qari berhasil diubah';
        document.body.appendChild(notification);

        // Remove notification after 3 seconds
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
</script>
@endif
@endsection
