<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PrayerTimeController extends Controller
{
    public function index(Request $request)
    {
        $city = $request->query('city', 'Jakarta');
        $country = $request->query('country', 'Indonesia');

        // Dapatkan tanggal hari ini
        $today = Carbon::now();
        $month = $today->month;
        $year = $today->year;

        // Dapatkan data jadwal sholat dari API
        $response = $this->getPrayerTimes($city, $country, $month, $year);

        // Daftar kota populer di Indonesia
        $popularCities = [
            'Jakarta', 'Surabaya', 'Bandung', 'Medan', 'Semarang',
            'Makassar', 'Palembang', 'Tangerang', 'Depok', 'Bekasi'
        ];

        return view('prayer-times.index', [
            'city' => $city,
            'country' => $country,
            'prayerTimes' => $response,
            'today' => $today,
            'popularCities' => $popularCities
        ]);
    }

    private function getPrayerTimes($city, $country, $month, $year)
    {
        // Contoh implementasi dengan Aladhan API
        // Dalam implementasi nyata, Anda mungkin perlu mendaftar untuk API key
        try {
            $response = Http::get("http://api.aladhan.com/v1/calendarByCity", [
                'city' => $city,
                'country' => $country,
                'method' => 20, // Method 2 adalah metode Hanafi
                'month' => $month,
                'year' => $year
            ]);

            if ($response->successful()) {
                return $response->json()['data'];
            }

            return [];
        } catch (\Exception $e) {
            // Log error dan kembalikan array kosong
            Log::error('Error fetching prayer times: ' . $e->getMessage());
            return [];
        }
    }

    public function calendar(Request $request)
    {
        $city = $request->query('city', 'Jakarta');
        $country = $request->query('country', 'Indonesia');
        $month = $request->query('month', Carbon::now()->month);
        $year = $request->query('year', Carbon::now()->year);

        // Dapatkan data jadwal sholat bulanan
        $response = $this->getPrayerTimes($city, $country, $month, $year);

        return view('prayer-times.calendar', [
            'city' => $city,
            'country' => $country,
            'prayerTimes' => $response,
            'month' => $month,
            'year' => $year
        ]);
    }
}
