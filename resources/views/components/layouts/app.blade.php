<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'GDA Private - SMA Global Darussalam' }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-slate-50 flex flex-col selection:bg-blue-600 selection:text-white">
    <!-- Navbar Resmi GDA -->
    <header class="bg-slate-900 border-b border-slate-800 text-white sticky top-0 z-40 shadow-xs">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-600 flex items-center justify-center font-bold text-white text-lg tracking-wider border border-blue-400/30 shadow-xs">
                    GDA
                </div>
                <div>
                    <h1 class="text-sm font-bold tracking-tight text-white leading-tight">SMA GLOBAL DARUSSALAM</h1>
                    <p class="text-xs text-amber-400 font-medium tracking-wide">Portal Bimbingan Private</p>
                </div>
            </div>

            @auth
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <div class="text-xs font-semibold text-white">{{ Auth::user()->name }}</div>
                        <div class="text-[11px] text-slate-400">
                            {{ Auth::user()->isStudent() ? 'Siswa' : (Auth::user()->isTeacher() ? 'Tentor / Guru' : 'Admin') }}
                            @if(Auth::user()->nisn_or_nip)
                                &bull; {{ Auth::user()->nisn_or_nip }}
                            @endif
                        </div>
                    </div>

                    @if(Auth::user()->isAdmin())
                        <a href="{{ url('/admin') }}" class="text-xs font-semibold px-3 py-1.5 rounded-md bg-amber-500 hover:bg-amber-600 text-slate-950 transition-colors shadow-xs">
                            Panel Admin
                        </a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-xs font-medium px-3 py-1.5 rounded-md bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 hover:text-white transition-colors">
                            Keluar
                        </button>
                    </form>
                </div>
            @endauth
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 max-w-5xl w-full mx-auto px-4 sm:px-6 py-6 sm:py-8">
        {{ $slot }}
    </main>

    <!-- Footer Formal -->
    <footer class="border-t border-slate-200 bg-white py-6 text-center text-xs text-slate-500 mt-auto">
        <div class="max-w-5xl mx-auto px-4">
            <p>&copy; {{ date('Y') }} SMA Global Darussalam Yogyakarta &bull; Sistem Booking Private Siswa-Tentor</p>
            <p class="mt-1 text-[11px] text-slate-400">Direkayasa untuk koordinasi belajar yang presisi, bebas bentrok jadwal, dan tepat waktu.</p>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
