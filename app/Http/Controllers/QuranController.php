<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuranBookmark;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class QuranController extends Controller
{
    protected $apiUrl = 'https://api.alquran.cloud/v1';

    /**
     * Tampilkan daftar surah Al-Quran
     */
    public function index()
    {
        try {
            $response = Http::get($this->apiUrl . '/surah');
            $surahs = $response->json()['data'];

            // Mapping nama Surah dalam bahasa Indonesia
            $surahNames = [
                1 => 'Al-Fatihah',
                2 => 'Al-Baqarah',
                3 => 'Ali Imran',
                4 => 'An-Nisa',
                5 => 'Al-Maidah',
                6 => 'Al-An\'am',
                7 => 'Al-A\'raf',
                8 => 'Al-Anfal',
                9 => 'At-Taubah',
                10 => 'Yunus',
                11 => 'Hud',
                12 => 'Yusuf',
                13 => 'Ar-Ra\'d',
                14 => 'Ibrahim',
                15 => 'Al-Hijr',
                16 => 'An-Nahl',
                17 => 'Al-Isra',
                18 => 'Al-Kahf',
                19 => 'Maryam',
                20 => 'Taha',
                21 => 'Al-Anbiya',
                22 => 'Al-Hajj',
                23 => 'Al-Mu\'minun',
                24 => 'An-Nur',
                25 => 'Al-Furqan',
                26 => 'Asy-Syu\'ara',
                27 => 'An-Naml',
                28 => 'Al-Qasas',
                29 => 'Al-Ankabut',
                30 => 'Ar-Rum',
                31 => 'Luqman',
                32 => 'As-Sajdah',
                33 => 'Al-Ahzab',
                34 => 'Saba',
                35 => 'Fatir',
                36 => 'Yasin',
                37 => 'As-Saffat',
                38 => 'Sad',
                39 => 'Az-Zumar',
                40 => 'Ghafir',
                41 => 'Fussilat',
                42 => 'Asy-Syura',
                43 => 'Az-Zukhruf',
                44 => 'Ad-Dukhan',
                45 => 'Al-Jasiyah',
                46 => 'Al-Ahqaf',
                47 => 'Muhammad',
                48 => 'Al-Fath',
                49 => 'Al-Hujurat',
                50 => 'Qaf',
                51 => 'Az-Zariyat',
                52 => 'At-Tur',
                53 => 'An-Najm',
                54 => 'Al-Qamar',
                55 => 'Ar-Rahman',
                56 => 'Al-Waqi\'ah',
                57 => 'Al-Hadid',
                58 => 'Al-Mujadilah',
                59 => 'Al-Hasyr',
                60 => 'Al-Mumtahanah',
                61 => 'As-Saff',
                62 => 'Al-Jumu\'ah',
                63 => 'Al-Munafiqun',
                64 => 'At-Taghabun',
                65 => 'At-Talaq',
                66 => 'At-Tahrim',
                67 => 'Al-Mulk',
                68 => 'Al-Qalam',
                69 => 'Al-Haqqah',
                70 => 'Al-Ma\'arij',
                71 => 'Nuh',
                72 => 'Al-Jinn',
                73 => 'Al-Muzzammil',
                74 => 'Al-Muddaththir',
                75 => 'Al-Qiyamah',
                76 => 'Al-Insan',
                77 => 'Al-Mursalat',
                78 => 'An-Naba',
                79 => 'An-Nazi\'at',
                80 => 'Abasa',
                81 => 'At-Takwir',
                82 => 'Al-Infitar',
                83 => 'Al-Mutaffifin',
                84 => 'Al-Insyiqaq',
                85 => 'Al-Buruj',
                86 => 'At-Tariq',
                87 => 'Al-A\'la',
                88 => 'Al-Ghasyiyah',
                89 => 'Al-Fajr',
                90 => 'Al-Balad',
                91 => 'Asy-Syams',
                92 => 'Al-Lail',
                93 => 'Ad-Duha',
                94 => 'Al-Insyirah',
                95 => 'At-Tin',
                96 => 'Al-Alaq',
                97 => 'Al-Qadr',
                98 => 'Al-Bayyinah',
                99 => 'Az-Zalzalah',
                100 => 'Al-Adiyat',
                101 => 'Al-Qari\'ah',
                102 => 'At-Takathur',
                103 => 'Al-Asr',
                104 => 'Al-Humazah',
                105 => 'Al-Fil',
                106 => 'Quraisy',
                107 => 'Al-Ma\'un',
                108 => 'Al-Kausar',
                109 => 'Al-Kafirun',
                110 => 'An-Nasr',
                111 => 'Al-Lahab',
                112 => 'Al-Ikhlas',
                113 => 'Al-Falaq',
                114 => 'An-Nas',
            ];

            // Tambahkan nama Indonesia ke setiap surah
            foreach ($surahs as &$surah) {
                $surah['indonesianName'] = $surahNames[$surah['number']] ?? $surah['englishName'];
            }

            return view('quran.index', compact('surahs'));
        } catch (\Exception $e) {
            return view('quran.index')->with('error', 'Gagal memuat data surah: ' . $e->getMessage());
        }
    }

    public function showSurah($surahNumber)
    {
        try {
            // Get basic surah information
            $surahResponse = Http::get($this->apiUrl . '/surah/' . $surahNumber);
            $surah = $surahResponse->json()['data'];

            // Get Arabic text with Alafasy audio
            $ayahResponse = Http::get($this->apiUrl . '/surah/' . $surahNumber . '/ar.alafasy');
            $ayahs = $ayahResponse->json()['data']['ayahs'];

            // Get Indonesian translation
            $translationResponse = Http::get($this->apiUrl . '/surah/' . $surahNumber . '/id.indonesian');
            $translations = $translationResponse->json()['data']['ayahs'];

            // Get Latin transliteration
            $transliterationResponse = Http::get($this->apiUrl . '/surah/' . $surahNumber . '/en.transliteration');
            $transliterations = $transliterationResponse->json()['data']['ayahs'];

            return view('quran.show', compact('surah', 'ayahs', 'translations', 'transliterations'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat surah: ' . $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        if (empty($keyword)) {
            return redirect()->route('quran.index');
        }

        try {
            // Get all surahs first
            $response = Http::get($this->apiUrl . '/surah');
            $allSurahs = $response->json()['data'];

            // Mapping nama Surah dalam bahasa Indonesia
            $surahNames = [
                1 => 'Al-Fatihah',
                2 => 'Al-Baqarah',
                3 => 'Ali Imran',
                4 => 'An-Nisa',
                5 => 'Al-Maidah',
                6 => 'Al-An\'am',
                7 => 'Al-A\'raf',
                8 => 'Al-Anfal',
                9 => 'At-Taubah',
                10 => 'Yunus',
                11 => 'Hud',
                12 => 'Yusuf',
                13 => 'Ar-Ra\'d',
                14 => 'Ibrahim',
                15 => 'Al-Hijr',
                16 => 'An-Nahl',
                17 => 'Al-Isra',
                18 => 'Al-Kahf',
                19 => 'Maryam',
                20 => 'Taha',
                21 => 'Al-Anbiya',
                22 => 'Al-Hajj',
                23 => 'Al-Mu\'minun',
                24 => 'An-Nur',
                25 => 'Al-Furqan',
                26 => 'Asy-Syu\'ara',
                27 => 'An-Naml',
                28 => 'Al-Qasas',
                29 => 'Al-Ankabut',
                30 => 'Ar-Rum',
                31 => 'Luqman',
                32 => 'As-Sajdah',
                33 => 'Al-Ahzab',
                34 => 'Saba',
                35 => 'Fatir',
                36 => 'Yasin',
                37 => 'As-Saffat',
                38 => 'Sad',
                39 => 'Az-Zumar',
                40 => 'Ghafir',
                41 => 'Fussilat',
                42 => 'Asy-Syura',
                43 => 'Az-Zukhruf',
                44 => 'Ad-Dukhan',
                45 => 'Al-Jasiyah',
                46 => 'Al-Ahqaf',
                47 => 'Muhammad',
                48 => 'Al-Fath',
                49 => 'Al-Hujurat',
                50 => 'Qaf',
                51 => 'Az-Zariyat',
                52 => 'At-Tur',
                53 => 'An-Najm',
                54 => 'Al-Qamar',
                55 => 'Ar-Rahman',
                56 => 'Al-Waqi\'ah',
                57 => 'Al-Hadid',
                58 => 'Al-Mujadilah',
                59 => 'Al-Hasyr',
                60 => 'Al-Mumtahanah',
                61 => 'As-Saff',
                62 => 'Al-Jumu\'ah',
                63 => 'Al-Munafiqun',
                64 => 'At-Taghabun',
                65 => 'At-Talaq',
                66 => 'At-Tahrim',
                67 => 'Al-Mulk',
                68 => 'Al-Qalam',
                69 => 'Al-Haqqah',
                70 => 'Al-Ma\'arij',
                71 => 'Nuh',
                72 => 'Al-Jinn',
                73 => 'Al-Muzzammil',
                74 => 'Al-Muddaththir',
                75 => 'Al-Qiyamah',
                76 => 'Al-Insan',
                77 => 'Al-Mursalat',
                78 => 'An-Naba',
                79 => 'An-Nazi\'at',
                80 => 'Abasa',
                81 => 'At-Takwir',
                82 => 'Al-Infitar',
                83 => 'Al-Mutaffifin',
                84 => 'Al-Insyiqaq',
                85 => 'Al-Buruj',
                86 => 'At-Tariq',
                87 => 'Al-A\'la',
                88 => 'Al-Ghasyiyah',
                89 => 'Al-Fajr',
                90 => 'Al-Balad',
                91 => 'Asy-Syams',
                92 => 'Al-Lail',
                93 => 'Ad-Duha',
                94 => 'Al-Insyirah',
                95 => 'At-Tin',
                96 => 'Al-Alaq',
                97 => 'Al-Qadr',
                98 => 'Al-Bayyinah',
                99 => 'Az-Zalzalah',
                100 => 'Al-Adiyat',
                101 => 'Al-Qari\'ah',
                102 => 'At-Takathur',
                103 => 'Al-Asr',
                104 => 'Al-Humazah',
                105 => 'Al-Fil',
                106 => 'Quraisy',
                107 => 'Al-Ma\'un',
                108 => 'Al-Kausar',
                109 => 'Al-Kafirun',
                110 => 'An-Nasr',
                111 => 'Al-Lahab',
                112 => 'Al-Ikhlas',
                113 => 'Al-Falaq',
                114 => 'An-Nas',
            ];

            // Tambahkan nama Indonesia ke setiap surah
            foreach ($allSurahs as &$surah) {
                $surah['indonesianName'] = $surahNames[$surah['number']] ?? $surah['englishName'];
            }

            // Filter surahs based on keyword (case-insensitive)
            // Termasuk mencari berdasarkan nama Indonesia
            $results = array_filter($allSurahs, function ($surah) use ($keyword) {
                return (
                    stripos($surah['name'], $keyword) !== false ||
                    stripos($surah['englishName'], $keyword) !== false ||
                    stripos($surah['englishNameTranslation'], $keyword) !== false ||
                    stripos($surah['indonesianName'], $keyword) !== false || // Tambahkan pencarian nama Indonesia
                    (string)$surah['number'] === $keyword
                );
            });

            return view('quran.search', compact('results', 'keyword'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal melakukan pencarian surah: ' . $e->getMessage());
        }
    }

    public function juz()
    {
        try {
            $juzs = [];
            for ($i = 1; $i <= 30; $i++) {
                $juzs[] = [
                    'number' => $i,
                    'start' => 'Surah ' . $this->getJuzStartSurah($i),
                    'end' => 'Surah ' . $this->getJuzEndSurah($i),
                ];
            }

            return view('quran.juz', compact('juzs'));
        } catch (\Exception $e) {
            return view('quran.juz')->with('error', 'Gagal memuat data juz: ' . $e->getMessage());
        }
    }

    public function showJuz($juzNumber)
    {
        try {
            $response = Http::get($this->apiUrl . '/juz/' . $juzNumber . '/ar.alafasy');
            $juz = $response->json()['data'];

            // Mapping nama Surah dalam bahasa Indonesia
            $surahNames = [
                1 => 'Al-Fatihah',
                2 => 'Al-Baqarah',
                3 => 'Ali Imran',
                4 => 'An-Nisa',
                5 => 'Al-Maidah',
                6 => 'Al-An\'am',
                7 => 'Al-A\'raf',
                8 => 'Al-Anfal',
                9 => 'At-Taubah',
                10 => 'Yunus',
                11 => 'Hud',
                12 => 'Yusuf',
                13 => 'Ar-Ra\'d',
                14 => 'Ibrahim',
                15 => 'Al-Hijr',
                16 => 'An-Nahl',
                17 => 'Al-Isra',
                18 => 'Al-Kahf',
                19 => 'Maryam',
                20 => 'Taha',
                21 => 'Al-Anbiya',
                22 => 'Al-Hajj',
                23 => 'Al-Mu\'minun',
                24 => 'An-Nur',
                25 => 'Al-Furqan',
                26 => 'Asy-Syu\'ara',
                27 => 'An-Naml',
                28 => 'Al-Qasas',
                29 => 'Al-Ankabut',
                30 => 'Ar-Rum',
                31 => 'Luqman',
                32 => 'As-Sajdah',
                33 => 'Al-Ahzab',
                34 => 'Saba',
                35 => 'Fatir',
                36 => 'Yasin',
                37 => 'As-Saffat',
                38 => 'Sad',
                39 => 'Az-Zumar',
                40 => 'Ghafir',
                41 => 'Fussilat',
                42 => 'Asy-Syura',
                43 => 'Az-Zukhruf',
                44 => 'Ad-Dukhan',
                45 => 'Al-Jasiyah',
                46 => 'Al-Ahqaf',
                47 => 'Muhammad',
                48 => 'Al-Fath',
                49 => 'Al-Hujurat',
                50 => 'Qaf',
                51 => 'Az-Zariyat',
                52 => 'At-Tur',
                53 => 'An-Najm',
                54 => 'Al-Qamar',
                55 => 'Ar-Rahman',
                56 => 'Al-Waqi\'ah',
                57 => 'Al-Hadid',
                58 => 'Al-Mujadilah',
                59 => 'Al-Hasyr',
                60 => 'Al-Mumtahanah',
                61 => 'As-Saff',
                62 => 'Al-Jumu\'ah',
                63 => 'Al-Munafiqun',
                64 => 'At-Taghabun',
                65 => 'At-Talaq',
                66 => 'At-Tahrim',
                67 => 'Al-Mulk',
                68 => 'Al-Qalam',
                69 => 'Al-Haqqah',
                70 => 'Al-Ma\'arij',
                71 => 'Nuh',
                72 => 'Al-Jinn',
                73 => 'Al-Muzzammil',
                74 => 'Al-Muddaththir',
                75 => 'Al-Qiyamah',
                76 => 'Al-Insan',
                77 => 'Al-Mursalat',
                78 => 'An-Naba',
                79 => 'An-Nazi\'at',
                80 => 'Abasa',
                81 => 'At-Takwir',
                82 => 'Al-Infitar',
                83 => 'Al-Mutaffifin',
                84 => 'Al-Insyiqaq',
                85 => 'Al-Buruj',
                86 => 'At-Tariq',
                87 => 'Al-A\'la',
                88 => 'Al-Ghasyiyah',
                89 => 'Al-Fajr',
                90 => 'Al-Balad',
                91 => 'Asy-Syams',
                92 => 'Al-Lail',
                93 => 'Ad-Duha',
                94 => 'Al-Insyirah',
                95 => 'At-Tin',
                96 => 'Al-Alaq',
                97 => 'Al-Qadr',
                98 => 'Al-Bayyinah',
                99 => 'Az-Zalzalah',
                100 => 'Al-Adiyat',
                101 => 'Al-Qari\'ah',
                102 => 'At-Takathur',
                103 => 'Al-Asr',
                104 => 'Al-Humazah',
                105 => 'Al-Fil',
                106 => 'Quraisy',
                107 => 'Al-Ma\'un',
                108 => 'Al-Kausar',
                109 => 'Al-Kafirun',
                110 => 'An-Nasr',
                111 => 'Al-Lahab',
                112 => 'Al-Ikhlas',
                113 => 'Al-Falaq',
                114 => 'An-Nas',
            ];

            // Tambahkan nama Surah ke setiap ayat
            foreach ($juz['ayahs'] as &$ayah) {
                $ayah['surah']['name_id'] = $surahNames[$ayah['surah']['number']] ?? 'Tidak Diketahui';
            }

            $translationResponse = Http::get($this->apiUrl . '/juz/' . $juzNumber . '/id.indonesian');
            $translations = $translationResponse->json()['data']['ayahs'] ?? [];

            return view('quran.juz-detail', compact('juz', 'translations'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat juz: ' . $e->getMessage());
        }
    }

    private function getJuzStartSurah($juz)
    {
        $juzMap = [
            1 => 1,
            2 => 2,
            3 => 2,
            4 => 3,
            5 => 4,
            6 => 4,
            7 => 5,
            8 => 6,
            9 => 7,
            10 => 8,
            11 => 9,
            12 => 11,
            13 => 12,
            14 => 15,
            15 => 17,
            16 => 18,
            17 => 21,
            18 => 23,
            19 => 25,
            20 => 27,
            21 => 29,
            22 => 33,
            23 => 36,
            24 => 39,
            25 => 41,
            26 => 46,
            27 => 51,
            28 => 58,
            29 => 67,
            30 => 78,
        ];
        return $juzMap[$juz];
    }

    private function getJuzEndSurah($juz)
    {
        $juzMap = [
            1 => 2,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 4,
            6 => 5,
            7 => 6,
            8 => 7,
            9 => 8,
            10 => 9,
            11 => 11,
            12 => 12,
            13 => 14,
            14 => 16,
            15 => 18,
            16 => 20,
            17 => 22,
            18 => 25,
            19 => 27,
            20 => 29,
            21 => 33,
            22 => 35,
            23 => 39,
            24 => 41,
            25 => 45,
            26 => 51,
            27 => 57,
            28 => 66,
            29 => 77,
            30 => 114,
        ];
        return $juzMap[$juz];
    }
}
