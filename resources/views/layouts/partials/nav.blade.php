<nav class="bg-emerald-600 text-white shadow-lg">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center">
        <!-- Logo atau Nama Aplikasi -->
        <a href="{{ route('home') }}" class="flex items-center space-x-2">
            <svg class="h-8 w-8 text-white" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2L7 6v2H5v4H3v10h18V12h-2V8h-2V6l-5-4zM7 12v8H5v-8h2zm4 0v8H9v-8h2zm4 0v8h-2v-8h2zm4 0v8h-2v-8h2z" fill="currentColor"/>
            </svg>
            <span class="text-xl font-bold text-white">Jadwal Sholat dan Imsakiyah</span>
        </a>

        <!-- Tombol Menu untuk Mobile -->
        <button class="md:hidden p-2 focus:outline-none" id="mobile-menu-button">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
        </button>

        <!-- Menu untuk Desktop -->
        <div class="hidden md:flex items-center space-x-4" id="desktop-menu">
            <a href="{{ route('home') }}" class="hover:text-emerald-200 transition duration-300">Hari Ini</a>
            <a href="{{ route('calendar') }}" class="hover:text-emerald-200 transition duration-300">Kalender Bulanan</a>

            <!-- Dropdown Menu untuk Al-Quran -->
            <div class="relative group">
                <button class="flex items-center hover:text-emerald-200 transition duration-300">
                    Al-Quran
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity duration-300">
                    <a href="{{ route('quran.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-100">Daftar Surah</a>
                    <a href="{{ route('quran.juz') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-100">Daftar Juz</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu untuk Mobile -->
    <div class="md:hidden hidden" id="mobile-menu">
        <a href="{{ route('home') }}" class="block px-4 py-2 hover:bg-emerald-700 transition duration-300">Hari Ini</a>
        <a href="{{ route('calendar') }}" class="block px-4 py-2 hover:bg-emerald-700 transition duration-300">Kalender Bulanan</a>
        <div class="px-4 py-2">
            <button class="w-full flex justify-between items-center hover:text-emerald-200 transition duration-300" id="mobile-dropdown-button">
                Al-Quran
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div class="hidden mt-2 space-y-1" id="mobile-dropdown">
                <a href="{{ route('quran.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-100">Daftar Surah</a>
                <a href="{{ route('quran.juz') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-emerald-100">Daftar Juz</a>
            </div>
        </div>
    </div>
</nav>

<script>
    // Script untuk toggle menu mobile
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        var mobileMenu = document.getElementById('mobile-menu');
        mobileMenu.classList.toggle('hidden');
    });

    // Script untuk toggle dropdown mobile
    document.getElementById('mobile-dropdown-button').addEventListener('click', function() {
        var mobileDropdown = document.getElementById('mobile-dropdown');
        mobileDropdown.classList.toggle('hidden');
    });
</script>
