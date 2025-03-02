@extends('layouts.app')

@section('title', 'Kalender Jadwal Sholat & Imsak')

@section('styles')
<style>
    body {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }
    main {
        flex: 1 0 auto;
    }
    footer {
        width: 100%;
        position: relative;
        left: 0;
        bottom: 0;
    }

    /* Style untuk scrollbar horizontal pada mobile */
    .table-scroll-container {
        position: relative;
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch; /* Untuk scroll yang lebih halus di iOS */
    }

    .table-scroll-indicator {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        background-color: #10b981;
        width: 25%;
        opacity: 0;
        transition: opacity 0.3s;
    }

    /* Card view untuk tampilan mobile */
    .prayer-card {
        display: none;
    }

    /* Media query untuk perangkat mobile */
    @media (max-width: 768px) {
        .desktop-table {
            display: none;
        }

        .prayer-card {
            display: block;
            margin-bottom: 1rem;
            padding: 1rem;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            background-color: white;
            border-left: 4px solid #10b981;
        }

        .prayer-card.today {
            background-color: #ecfdf5;
            border: 1px solid #10b981;
            border-left: 4px solid #10b981;
        }

        .prayer-time-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .prayer-time-item {
            display: flex;
            flex-direction: column;
        }

        .prayer-time-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #4b5563;
        }

        .prayer-time-value {
            font-weight: 500;
        }

        .date-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .date-container .date {
            font-weight: 600;
        }

        .date-container .day {
            color: #6b7280;
        }
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h1 class="text-2xl font-bold text-emerald-700 mb-4">Kalender Jadwal Sholat & Imsak</h1>
            <div class="mb-4">
                <form action="{{ route('calendar') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-1">Kota</label>
                        <input type="text" name="city" id="city" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" value="{{ $city }}">
                    </div>
                    <div class="flex-1">
                        <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Negara</label>
                        <input type="text" name="country" id="country" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200" value="{{ $country }}">
                    </div>
                    <div class="flex-1">
                        <label for="month" class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                        <select name="month" id="month" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                            @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="flex-1">
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                        <select name="year" id="year" class="w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                            @for($i = date('Y') - 1; $i <= date('Y') + 1; $i++)
                                <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="self-end">
                        <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                            Tampilkan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-emerald-600 text-white p-4">
                <h2 class="text-xl font-bold">{{ date('F Y', mktime(0, 0, 0, $month, 1, $year)) }}</h2>
                <p>{{ $city }}, {{ $country }}</p>
            </div>

            @if(!empty($prayerTimes))
            <!-- Desktop View -->
            <div class="p-4 desktop-table">
                <div class="table-scroll-container">
                    <div class="table-scroll-indicator"></div>
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="sticky top-0 px-3 py-3 bg-gradient-to-r from-emerald-600 to-emerald-500 text-white text-left text-xs font-medium uppercase tracking-wider rounded-tl-lg">Tanggal</th>
                                <th class="sticky top-0 px-3 py-3 bg-gradient-to-r from-emerald-600 to-emerald-500 text-white text-left text-xs font-medium uppercase tracking-wider">Imsak</th>
                                <th class="sticky top-0 px-3 py-3 bg-gradient-to-r from-emerald-600 to-emerald-500 text-white text-left text-xs font-medium uppercase tracking-wider">Subuh</th>
                                <th class="sticky top-0 px-3 py-3 bg-gradient-to-r from-emerald-600 to-emerald-500 text-white text-left text-xs font-medium uppercase tracking-wider">Terbit</th>
                                <th class="sticky top-0 px-3 py-3 bg-gradient-to-r from-emerald-600 to-emerald-500 text-white text-left text-xs font-medium uppercase tracking-wider">Dzuhur</th>
                                <th class="sticky top-0 px-3 py-3 bg-gradient-to-r from-emerald-600 to-emerald-500 text-white text-left text-xs font-medium uppercase tracking-wider">Ashar</th>
                                <th class="sticky top-0 px-3 py-3 bg-gradient-to-r from-emerald-600 to-emerald-500 text-white text-left text-xs font-medium uppercase tracking-wider">Maghrib</th>
                                <th class="sticky top-0 px-3 py-3 bg-gradient-to-r from-emerald-600 to-emerald-500 text-white text-left text-xs font-medium uppercase tracking-wider rounded-tr-lg">Isya</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach($prayerTimes as $day)
                                @php
                                    $date = Carbon\Carbon::parse($day['date']['readable']);
                                    $isToday = $date->isToday();

                                    // Konversi nama hari ke Bahasa Indonesia
                                    $dayOfWeek = $date->format('w');
                                    $indonesianDays = [
                                        '0' => 'Minggu',
                                        '1' => 'Senin',
                                        '2' => 'Selasa',
                                        '3' => 'Rabu',
                                        '4' => 'Kamis',
                                        '5' => 'Jumat',
                                        '6' => 'Sabtu'
                                    ];
                                    $dayName = $indonesianDays[$dayOfWeek];
                                @endphp
                                <tr class="{{ $isToday ? 'bg-emerald-50 ring-2 ring-emerald-300' : 'hover:bg-gray-50' }} transition-colors">
                                    <td class="px-3 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $date->format('d') }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $dayName }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $day['timings']['Imsak'] }}
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $day['timings']['Fajr'] }}
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $day['timings']['Sunrise'] }}
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $day['timings']['Dhuhr'] }}
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $day['timings']['Asr'] }}
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $day['timings']['Maghrib'] }}
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $day['timings']['Isha'] }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mobile View - Card Based -->
            <div class="p-4">
                <div class="mobile-cards">
                    @foreach($prayerTimes as $day)
                        @php
                            $date = Carbon\Carbon::parse($day['date']['readable']);
                            $isToday = $date->isToday();

                            // Konversi nama hari ke Bahasa Indonesia
                            $dayOfWeek = $date->format('w');
                            $indonesianDays = [
                                '0' => 'Minggu',
                                '1' => 'Senin',
                                '2' => 'Selasa',
                                '3' => 'Rabu',
                                '4' => 'Kamis',
                                '5' => 'Jumat',
                                '6' => 'Sabtu'
                            ];
                            $dayName = $indonesianDays[$dayOfWeek];
                        @endphp
                        <div class="prayer-card {{ $isToday ? 'today' : '' }}">
                            <div class="date-container">
                                <div>
                                    <span class="date">{{ $date->format('d') }}</span>
                                    <span class="day">{{ $dayName }}</span>
                                </div>
                                @if($isToday)
                                    <span class="bg-emerald-100 text-emerald-800 text-xs px-2 py-1 rounded-full">Hari Ini</span>
                                @endif
                            </div>

                            <div class="prayer-time-grid">
                                <div class="prayer-time-item">
                                    <span class="prayer-time-label">Imsak</span>
                                    <span class="prayer-time-value">{{ $day['timings']['Imsak'] }}</span>
                                </div>
                                <div class="prayer-time-item">
                                    <span class="prayer-time-label">Subuh</span>
                                    <span class="prayer-time-value">{{ $day['timings']['Fajr'] }}</span>
                                </div>
                                <div class="prayer-time-item">
                                    <span class="prayer-time-label">Terbit</span>
                                    <span class="prayer-time-value">{{ $day['timings']['Sunrise'] }}</span>
                                </div>
                                <div class="prayer-time-item">
                                    <span class="prayer-time-label">Dzuhur</span>
                                    <span class="prayer-time-value">{{ $day['timings']['Dhuhr'] }}</span>
                                </div>
                                <div class="prayer-time-item">
                                    <span class="prayer-time-label">Ashar</span>
                                    <span class="prayer-time-value">{{ $day['timings']['Asr'] }}</span>
                                </div>
                                <div class="prayer-time-item">
                                    <span class="prayer-time-label">Maghrib</span>
                                    <span class="prayer-time-value">{{ $day['timings']['Maghrib'] }}</span>
                                </div>
                                <div class="prayer-time-item">
                                    <span class="prayer-time-label">Isya</span>
                                    <span class="prayer-time-value">{{ $day['timings']['Isha'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @else
                <div class="p-6 text-center">
                    <p class="text-red-500">Gagal memuat data jadwal sholat. Silakan coba lagi nanti.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    // Script untuk indikator scroll horizontal pada tabel
    document.addEventListener('DOMContentLoaded', function() {
        const scrollContainer = document.querySelector('.table-scroll-container');
        const scrollIndicator = document.querySelector('.table-scroll-indicator');

        if (scrollContainer && scrollIndicator) {
            scrollContainer.addEventListener('scroll', function() {
                const maxScrollLeft = scrollContainer.scrollWidth - scrollContainer.clientWidth;
                const percentageScrolled = (scrollContainer.scrollLeft / maxScrollLeft) * 100;

                scrollIndicator.style.left = percentageScrolled + '%';
                scrollIndicator.style.opacity = '1';

                // Sembunyikan indikator setelah 1.5 detik tanpa scrolling
                clearTimeout(scrollContainer.scrollTimeout);
                scrollContainer.scrollTimeout = setTimeout(function() {
                    scrollIndicator.style.opacity = '0';
                }, 1500);
            });

            // Periksa apakah tabel lebih lebar dari container
            if (scrollContainer.scrollWidth > scrollContainer.clientWidth) {
                scrollIndicator.style.opacity = '1';
                // Sembunyikan setelah 1.5 detik
                setTimeout(function() {
                    scrollIndicator.style.opacity = '0';
                }, 1500);
            }
        }
    });
</script>
@endsection
