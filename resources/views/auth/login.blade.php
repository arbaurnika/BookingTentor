<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Portal Bimbingan Private SMA Global Darussalam</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-slate-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8 selection:bg-blue-600 selection:text-white">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <!-- Logo & Header Sekolah -->
        <div class="flex justify-center">
            <div class="w-14 h-14 rounded-xl bg-slate-900 border border-blue-600/30 flex items-center justify-center text-white font-bold text-2xl tracking-wider shadow-sm">
                GDA
            </div>
        </div>
        <h2 class="mt-4 text-center text-xl font-bold tracking-tight text-slate-900">
            SMA Global Darussalam
        </h2>
        <p class="mt-1 text-center text-xs font-semibold uppercase tracking-wider text-amber-600">
            Sistem Booking Private Siswa & Tentor
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md px-4 sm:px-0">
        <div class="bg-white py-8 px-6 shadow-sm border border-slate-200 rounded-xl sm:px-10">
            @if(session('error'))
                <div class="mb-5 p-3.5 bg-red-50 border border-red-200 rounded-lg text-xs text-red-700 font-medium">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="mb-5 p-3.5 bg-emerald-50 border border-emerald-200 rounded-lg text-xs text-emerald-700 font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <div class="text-center mb-6">
                <h3 class="text-sm font-semibold text-slate-900">Masuk ke Portal</h3>
                <p class="text-xs text-slate-500 mt-1">Gunakan akun email resmi sekolah yang telah didaftarkan.</p>
            </div>

            <!-- Tombol Utama: Google Workspace SSO -->
            <a href="{{ route('login.google') }}" class="w-full flex items-center justify-center gap-3 py-2.5 px-4 rounded-lg border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 font-semibold text-xs shadow-xs transition-colors">
                <svg class="w-4 h-4" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                </svg>
                Masuk dengan Akun Google (@globaldarussalam.sch.id)
            </a>

            <!-- Divider -->
            <div class="relative my-7">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-slate-200"></div>
                </div>
                <div class="relative flex justify-center text-xs">
                    <span class="bg-white px-3 text-slate-400 font-medium">Atau Uji Coba Simulasi Lokal</span>
                </div>
            </div>

            <!-- Quick Dev / Demo Login -->
            <div class="space-y-2">
                <p class="text-[11px] font-semibold text-slate-600 mb-2">Simulasi Cepat (Klik untuk masuk langsung):</p>
                
                @foreach($demoUsers as $demo)
                    <a href="{{ route('dev.login', $demo->id) }}" class="w-full text-left px-3.5 py-2.5 rounded-lg border border-slate-200 hover:border-blue-500 hover:bg-blue-50/50 transition-all flex items-center justify-between group block">
                        <div>
                            <div class="text-xs font-semibold text-slate-900 group-hover:text-blue-700">
                                {{ $demo->name }}
                            </div>
                            <div class="text-[11px] text-slate-500">
                                {{ $demo->email }}
                            </div>
                        </div>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded {{ $demo->role === 'admin' ? 'bg-red-100 text-red-700' : ($demo->role === 'teacher' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800') }}">
                            {{ $demo->role }}
                        </span>
                    </a>
                @endforeach
            </div>

            <div class="mt-6 text-center">
                <a href="{{ url('/admin') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 hover:underline">
                    &rarr; Akses Panel Admin Sekolah Langsung
                </a>
            </div>
        </div>
    </div>
</body>
</html>
