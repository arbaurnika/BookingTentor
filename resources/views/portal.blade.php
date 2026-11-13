<x-layouts.app>
    @if(Auth::user()->isStudent())
        <livewire:student-booking-portal />
    @elseif(Auth::user()->isTeacher())
        <livewire:teacher-dashboard-portal />
    @else
        <div class="bg-white p-8 rounded-xl border border-slate-200 text-center">
            <h2 class="text-base font-bold text-slate-900">Selamat datang, Administrator</h2>
            <p class="text-xs text-slate-500 mt-1">Anda memiliki akses penuh untuk mengatur master data dan rekapitulasi.</p>
            <div class="mt-4">
                <a href="{{ url('/admin') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-700 hover:bg-blue-800 text-white font-semibold text-xs shadow-xs">
                    Buka Dashboard Admin Filament &rarr;
                </a>
            </div>
        </div>
    @endif
</x-layouts.app>
