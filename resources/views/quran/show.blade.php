<!-- resources/views/quran/show.blade.php -->
@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <!-- Surah Header -->
                <div class="text-center mb-8 pb-6 border-b">
                    <!-- Judul Surah -->
                    <h1 class="text-3xl font-bold mb-2 text-emerald-800">{{ $surah['name'] }}</h1>
                    <h2 class="text-xl mb-4">{{ $surah['englishName'] }} • {{ $surah['englishNameTranslation'] }}</h2>

                    <!-- Informasi Surah -->
                    <p class="text-sm text-gray-600">
                        @if($surah['revelationType'] === 'Meccan')
                            Makkiyah
                        @else
                            Madaniyah
                        @endif
                        • {{ $surah['numberOfAyahs'] }} Ayat
                    </p>
                </div>

                <!-- Ayat List -->
                <div>
                    @foreach($ayahs as $index => $ayah)
                        <div class="mb-8 pb-6 border-b border-gray-100 ayah-container" id="ayah-{{ $ayah['numberInSurah'] }}">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-10 h-10 flex items-center justify-center bg-emerald-100 rounded-full text-emerald-600 font-bold">
                                    {{ $ayah['numberInSurah'] }}
                                </div>
                                <div class="flex space-x-3">
                                    <button class="text-gray-500 hover:text-emerald-600 play-audio" data-audio="{{ $ayah['audio'] }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Teks Arab -->
                            <p class="text-right text-2xl leading-loose font-arabic mb-4">
                                @if($ayah['numberInSurah'] == 1 && $surah['number'] == 1)
                                    {{ str_replace('بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ', '', $ayah['text']) }}
                                @else
                                    {{ $ayah['text'] }}
                                @endif
                            </p>

                            <!-- Latin Transliteration -->
                            @if(isset($transliterations[$index]))
                                <p class="text-gray-700 italic mb-3">
                                    @if($ayah['numberInSurah'] == 1 && $surah['number'] == 1)
                                        {{ str_replace('Bismillāhir-Raḥmānir-Raḥīm', '', $transliterations[$index]['text']) }}
                                    @else
                                        {{ $transliterations[$index]['text'] }}
                                    @endif
                                </p>
                            @endif

                            <!-- Indonesian Translation -->
                            @if(isset($translations[$index]))
                                <p class="text-gray-700">
                                    @if($ayah['numberInSurah'] == 1 && $surah['number'] == 1)
                                        {{ str_replace('Dengan menyebut nama Allah Yang Maha Pengasih lagi Maha Penyayang', '', $translations[$index]['text']) }}
                                    @else
                                        {{ $translations[$index]['text'] }}
                                    @endif
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Navigasi antar surah -->
                <div class="mt-8 flex justify-between">
                    @if($surah['number'] > 1)
                        <a href="{{ route('quran.surah', $surah['number'] - 1) }}" class="px-4 py-2 bg-gray-100 rounded-md hover:bg-gray-200 text-gray-700">
                            &larr; Surah Sebelumnya
                        </a>
                    @else
                        <div></div>
                    @endif

                    @if($surah['number'] < 114)
                        <a href="{{ route('quran.surah', $surah['number'] + 1) }}" class="px-4 py-2 bg-gray-100 rounded-md hover:bg-gray-200 text-gray-700">
                            Surah Berikutnya &rarr;
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Audio Player -->
<div id="audio-player" class="fixed bottom-0 left-0 right-0 bg-white shadow-lg border-t p-3 hidden">
    <div class="max-w-4xl mx-auto flex items-center">
        <button id="play-pause" class="text-emerald-600 mr-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </button>
        <div class="flex-1">
            <div class="text-sm text-gray-600 mb-1" id="ayah-info">Surah Al-Fatihah - Ayat 1</div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div id="progress-bar" class="bg-emerald-600 h-2 rounded-full" style="width: 0%"></div>
            </div>
        </div>
        <button id="close-player" class="ml-4 text-gray-400 hover:text-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>
@endsection

<meta name="csrf-token" content="{{ csrf_token() }}">
@section('scripts')
<script>
    // Script untuk menangani fitur audio player
    document.addEventListener('DOMContentLoaded', function() {
        const audioPlayer = document.getElementById('audio-player');
        const playPauseBtn = document.getElementById('play-pause');
        const closePlayerBtn = document.getElementById('close-player');
        const progressBar = document.getElementById('progress-bar');
        const ayahInfo = document.getElementById('ayah-info');

        let audio = new Audio();
        let isPlaying = false;
        let currentAyahIndex = 0;
        let ayahElements = document.querySelectorAll('.ayah-container');

        // Event untuk play button di setiap ayat
        document.querySelectorAll('.play-audio').forEach(button => {
            button.addEventListener('click', function() {
                const audioUrl = this.getAttribute('data-audio');
                const ayahContainer = this.closest('.ayah-container');
                const ayahNumber = ayahContainer.id.replace('ayah-', '');

                if (audio.src === audioUrl && isPlaying) {
                    audio.pause();
                    isPlaying = false;
                    updatePlayPauseButton();
                } else {
                    audio.src = audioUrl;
                    audio.play();
                    isPlaying = true;
                    updatePlayPauseButton();

                    // Update informasi ayat yang diputar
                    ayahInfo.textContent = 'Surah {{ $surah["englishName"] }} - Ayat ' + ayahNumber;

                    // Tampilkan audio player
                    audioPlayer.classList.remove('hidden');
                }
            });
        });

        // Fungsi update tampilan button play/pause
        function updatePlayPauseButton() {
            if (isPlaying) {
                playPauseBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                `;
            } else {
                playPauseBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                `;
            }
        }

        // Event untuk tombol play/pause di player
        playPauseBtn.addEventListener('click', function() {
            if (isPlaying) {
                audio.pause();
                isPlaying = false;
            } else {
                audio.play();
                isPlaying = true;
            }
            updatePlayPauseButton();
        });

        // Event untuk tombol close di player
        closePlayerBtn.addEventListener('click', function() {
            audio.pause();
            isPlaying = false;
            audioPlayer.classList.add('hidden');
        });

        // Update progress bar saat audio diputar
        audio.addEventListener('timeupdate', function() {
            const progress = (audio.currentTime / audio.duration) * 100;
            progressBar.style.width = progress + '%';
        });

        // Saat audio selesai
        audio.addEventListener('ended', function() {
            if (currentAyahIndex < ayahElements.length - 1) {
                currentAyahIndex++;
                playAyah(currentAyahIndex);
            } else {
                isPlaying = false;
                updatePlayPauseButton();
                progressBar.style.width = '0%';
            }
        }); 
    });
</script>
@endsection
