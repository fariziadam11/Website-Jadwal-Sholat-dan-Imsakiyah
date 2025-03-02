@extends('layouts.app')

@section('title', 'Jadwal Sholat & Imsak')

@section('styles')
<style>
    .marquee-container {
        width: 100%;
    }

    .marquee-content {
        animation: marquee 20s linear infinite;
    }

    @keyframes marquee {
        0% { transform: translateX(100%); }
        100% { transform: translateX(-100%); }
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h1 class="text-2xl font-bold text-emerald-700 mb-4">Jadwal Sholat & Imsak</h1>
            <div class="mb-4">
                <form action="{{ route('home') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-1">Kota</label>
                        <select name="city" id="city" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                            @foreach($popularCities as $popularCity)
                                <option value="{{ $popularCity }}" {{ $city === $popularCity ? 'selected' : '' }}>
                                    {{ $popularCity }}
                                </option>
                            @endforeach
                            <option value="other" {{ !in_array($city, $popularCities) ? 'selected' : '' }}>Kota Lain</option>
                        </select>
                    </div>
                    <div class="flex-1 city-other-container" style="display: none;">
                        <label for="city_other" class="block text-sm font-medium text-gray-700 mb-1">Nama Kota</label>
                        <input type="text" name="city_other" id="city_other" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" value="{{ !in_array($city, $popularCities) ? $city : '' }}">
                    </div>
                    <div class="flex-1">
                        <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Negara</label>
                        <input type="text" name="country" id="country" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" value="{{ $country }}">
                    </div>
                    <div class="self-end">
                        <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                            Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-emerald-600 text-white p-4">
                <h2 class="text-xl font-bold">{{ $today->format('d F Y') }}</h2>
                <p>{{ $city }}, {{ $country }}</p>
            </div>

            @if(!empty($prayerTimes))
                @php
                    // Ambil data untuk hari ini
                    $todayDate = $today->format('d-m-Y');
                    $todayPrayerTimes = null;

                    foreach($prayerTimes as $day) {
                        $dayDate = Carbon\Carbon::parse($day['date']['readable'])->format('d-m-Y');
                        if ($dayDate === $todayDate) {
                            $todayPrayerTimes = $day;
                            break;
                        }
                    }
                @endphp

                @if($todayPrayerTimes)
                    <div class="p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="bg-emerald-50 rounded-lg p-4 flex items-center">
                                <div class="bg-emerald-100 text-emerald-700 p-3 rounded-full mr-4">
                                    <i class="fas fa-coffee"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Imsak</p>
                                    <p class="text-lg font-bold">{{ $todayPrayerTimes['timings']['Imsak'] }}</p>
                                </div>
                            </div>

                            <div class="bg-emerald-50 rounded-lg p-4 flex items-center">
                                <div class="bg-emerald-100 text-emerald-700 p-3 rounded-full mr-4">
                                    <i class="fas fa-sun"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Subuh</p>
                                    <p class="text-lg font-bold">{{ $todayPrayerTimes['timings']['Fajr'] }}</p>
                                </div>
                            </div>

                            <div class="bg-emerald-50 rounded-lg p-4 flex items-center">
                                <div class="bg-emerald-100 text-emerald-700 p-3 rounded-full mr-4">
                                    <i class="fas fa-cloud-sun"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Terbit</p>
                                    <p class="text-lg font-bold">{{ $todayPrayerTimes['timings']['Sunrise'] }}</p>
                                </div>
                            </div>

                            <div class="bg-emerald-50 rounded-lg p-4 flex items-center">
                                <div class="bg-emerald-100 text-emerald-700 p-3 rounded-full mr-4">
                                    <i class="fas fa-sun"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Dzuhur</p>
                                    <p class="text-lg font-bold">{{ $todayPrayerTimes['timings']['Dhuhr'] }}</p>
                                </div>
                            </div>

                            <div class="bg-emerald-50 rounded-lg p-4 flex items-center">
                                <div class="bg-emerald-100 text-emerald-700 p-3 rounded-full mr-4">
                                    <i class="fas fa-sun"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Ashar</p>
                                    <p class="text-lg font-bold">{{ $todayPrayerTimes['timings']['Asr'] }}</p>
                                </div>
                            </div>

                            <div class="bg-emerald-50 rounded-lg p-4 flex items-center">
                                <div class="bg-emerald-100 text-emerald-700 p-3 rounded-full mr-4">
                                    <i class="fas fa-cloud-sun"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Maghrib</p>
                                    <p class="text-lg font-bold">{{ $todayPrayerTimes['timings']['Maghrib'] }}</p>
                                </div>
                            </div>

                            <div class="bg-emerald-50 rounded-lg p-4 flex items-center">
                                <div class="bg-emerald-100 text-emerald-700 p-3 rounded-full mr-4">
                                    <i class="fas fa-moon"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Isya</p>
                                    <p class="text-lg font-bold">{{ $todayPrayerTimes['timings']['Isha'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="p-6 text-center">
                        <p class="text-red-500">Data jadwal sholat tidak ditemukan untuk hari ini.</p>
                    </div>
                @endif
            @else
                <div class="p-6 text-center">
                    <p class="text-red-500">Gagal memuat data jadwal sholat. Silakan coba lagi nanti.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle custom city selection
        const citySelect = document.getElementById('city');
        const cityOtherContainer = document.querySelector('.city-other-container');

        function toggleCityOther() {
            if (citySelect.value === 'other') {
                cityOtherContainer.style.display = 'block';
            } else {
                cityOtherContainer.style.display = 'none';
            }
        }

        toggleCityOther();
        citySelect.addEventListener('change', toggleCityOther);
    });

    // Add prayer time notification functionality
    function checkPrayerTime() {
        const now = new Date();
        const currentTime = now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');

        const prayerTimes = {
            'Fajr': '{{ $todayPrayerTimes["timings"]["Fajr"] ?? "" }}',
            'Dhuhr': '{{ $todayPrayerTimes["timings"]["Dhuhr"] ?? "" }}',
            'Asr': '{{ $todayPrayerTimes["timings"]["Asr"] ?? "" }}',
            'Maghrib': '{{ $todayPrayerTimes["timings"]["Maghrib"] ?? "" }}',
            'Isha': '{{ $todayPrayerTimes["timings"]["Isha"] ?? "" }}'
        };

        const prayerNames = {
            'Fajr': 'Subuh',
            'Dhuhr': 'Dzuhur',
            'Asr': 'Ashar',
            'Maghrib': 'Maghrib',
            'Isha': 'Isya'
        };

        for (const [prayer, time] of Object.entries(prayerTimes)) {
            if (time && currentTime === time.substring(0, 5)) {
                const alert = document.getElementById('prayer-alert');
                const message = document.getElementById('prayer-message');
                message.textContent = `🕌 Telah masuk waktu sholat ${prayerNames[prayer]}. Segera laksanakan sholat ${prayerNames[prayer]}! 🕌`;
                alert.classList.remove('hidden');
                break;
            }
        }
    }

    // Check prayer times every minute
    setInterval(checkPrayerTime, 60000);
    checkPrayerTime(); // Check immediately on page load
</script>
@endsection
