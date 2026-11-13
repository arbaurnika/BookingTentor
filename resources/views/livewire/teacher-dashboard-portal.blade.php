<div>
    @if($feedbackMessage)
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-center justify-between shadow-xs">
            <span>{{ $feedbackMessage }}</span>
            <button wire:click="$set('feedbackMessage', null)" class="text-slate-400 hover:text-slate-600">&times;</button>
        </div>
    @endif

    <!-- Profile & Header Guru -->
    <div class="bg-white rounded-xl border border-slate-200 p-5 sm:p-6 mb-6 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                    Portal Tentor / Guru
                </span>
                <h2 class="text-lg font-bold text-slate-900 mt-1.5">{{ $teacher->name }}</h2>
                <p class="text-xs text-slate-500">
                    NIP: <span class="font-semibold text-slate-700">{{ $teacher->nisn_or_nip ?? '-' }}</span> &bull; 
                    Email: <span class="font-semibold text-slate-700">{{ $teacher->email }}</span>
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ url('/admin') }}" class="text-xs font-semibold px-3 py-1.5 rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 transition-colors">
                    Kelola Slot di Admin &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
            <div class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Sesi Mendatang</div>
            <div class="text-2xl font-bold text-blue-700 mt-1">{{ $upcomingBookings->count() }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">Siswa telah memesan</div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
            <div class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Siswa Bimbingan</div>
            <div class="text-2xl font-bold text-slate-900 mt-1">{{ $assignedStudents->count() }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">Siswa terpasang resmi</div>
        </div>
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs">
            <div class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Sesi Terlaksana</div>
            <div class="text-2xl font-bold text-emerald-600 mt-1">{{ $completedBookings->where('status', 'completed')->count() }}</div>
            <div class="text-[11px] text-slate-400 mt-0.5">Bimbingan selesai</div>
        </div>
    </div>

    <!-- Sesi Bimbingan Mendatang -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs mb-8">
        <div class="px-5 py-4 border-b border-slate-200">
            <h3 class="text-sm font-bold text-slate-900">Jadwal Bimbingan Mendatang</h3>
            <p class="text-xs text-slate-500">Daftar siswa yang telah memesan slot waktu bimbingan Anda.</p>
        </div>

        @if($upcomingBookings->isEmpty())
            <div class="p-12 text-center text-xs text-slate-400">
                Belum ada siswa yang memesan jadwal bimbingan untuk waktu dekat.
            </div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach($upcomingBookings as $booking)
                    <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50/50 transition-colors">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-lg bg-blue-50 border border-blue-200 text-blue-700 flex flex-col items-center justify-center font-bold shrink-0">
                                <span class="text-[10px] uppercase font-semibold leading-tight">{{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('M') }}</span>
                                <span class="text-lg leading-tight">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d') }}</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h4 class="text-sm font-bold text-slate-900">{{ $booking->student->name }}</h4>
                                    <span class="text-[10px] font-semibold px-2 py-0.5 rounded bg-slate-100 text-slate-700">
                                        NISN: {{ $booking->student->nisn_or_nip ?? '-' }}
                                    </span>
                                </div>
                                <div class="text-xs text-slate-600 mt-1">
                                    {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('l, d F Y') }} &bull; 
                                    <span class="font-semibold text-blue-700">{{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }} WIB</span> &bull; 
                                    <span>{{ $booking->slot->room_or_location ?? 'Ruang Diskusi' }}</span>
                                </div>
                                @if($booking->student_notes)
                                    <div class="text-xs text-slate-600 bg-slate-50 p-2.5 rounded-lg border border-slate-200 mt-2">
                                        <span class="font-semibold text-slate-800">Materi yang Ingin Dibahas:</span> "{{ $booking->student_notes }}"
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-2 self-end sm:self-auto">
                            <button 
                                wire:click="openCompleteModal({{ $booking->id }})"
                                class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white shadow-xs transition-colors">
                                Tandai Selesai
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Daftar Siswa Bimbingan yang Dipasangkan -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
        <div class="px-5 py-4 border-b border-slate-200">
            <h3 class="text-sm font-bold text-slate-900">Daftar Siswa Bimbingan Anda</h3>
            <p class="text-xs text-slate-500">Siswa yang ditugaskan resmi kepada Anda oleh pihak sekolah.</p>
        </div>

        <div class="divide-y divide-slate-100">
            @foreach($assignedStudents as $pair)
                <div class="p-4 flex items-center justify-between">
                    <div>
                        <div class="text-xs font-bold text-slate-900">{{ $pair->student->name }}</div>
                        <div class="text-[11px] text-slate-500">
                            NISN: {{ $pair->student->nisn_or_nip ?? '-' }} &bull; 
                            Mapel: <span class="font-semibold text-blue-700">{{ $pair->subject->name ?? 'Bimbingan' }}</span>
                        </div>
                    </div>
                    <div class="text-xs text-slate-600 font-medium">
                        {{ $pair->student->phone ?? '-' }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- MODAL TANDAI SELESAI -->
    @if($showCompleteModal)
        <div class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-slate-200">
                <h3 class="text-sm font-bold text-slate-900">Konfirmasi Sesi Bimbingan Selesai</h3>
                <p class="text-xs text-slate-500 mt-1">
                    Tandai sesi ini telah dilaksanakan dan berikan catatan perkembangan siswa singkat.
                </p>

                <div class="mt-4">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">
                        Catatan Perkembangan Siswa (Opsional):
                    </label>
                    <textarea 
                        wire:model="teacherFeedback"
                        placeholder="Contoh: Siswa sudah memahami konsep kalkulus dasar, perlu latihan tambahan di turunan berantai..."
                        rows="3"
                        class="w-full text-xs p-3 rounded-lg border border-slate-300 focus:outline-hidden focus:border-blue-600 focus:ring-1 focus:ring-blue-600"></textarea>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <button 
                        type="button" 
                        wire:click="closeCompleteModal"
                        class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-lg">
                        Batal
                    </button>
                    <button 
                        type="button" 
                        wire:click="markCompleted"
                        class="px-5 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-xs">
                        Simpan & Selesai
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
