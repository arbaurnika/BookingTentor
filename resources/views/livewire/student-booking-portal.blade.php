<div>
    <!-- Feedback Alert -->
    @if($feedbackMessage)
        <div class="mb-6 p-4 rounded-xl border flex items-center justify-between text-xs font-medium shadow-xs transition-all
            {{ $feedbackType === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-800' : ($feedbackType === 'error' ? 'bg-red-50 border-red-200 text-red-800' : 'bg-blue-50 border-blue-200 text-blue-800') }}">
            <div class="flex items-center gap-2">
                @if($feedbackType === 'success')
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                @else
                    <svg class="w-4 h-4 text-blue-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                @endif
                <span>{{ $feedbackMessage }}</span>
            </div>
            <button wire:click="$set('feedbackMessage', null)" class="text-slate-400 hover:text-slate-600">
                &times;
            </button>
        </div>
    @endif

    <!-- Profile & Header Siswa -->
    <div class="bg-white rounded-xl border border-slate-200 p-5 sm:p-6 mb-6 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                    Siswa Terverifikasi
                </span>
                <h2 class="text-lg font-bold text-slate-900 mt-1.5">Halo, {{ $student->name }}</h2>
                <p class="text-xs text-slate-500">
                    NISN: <span class="font-semibold text-slate-700">{{ $student->nisn_or_nip ?? '-' }}</span> &bull; 
                    Email: <span class="font-semibold text-slate-700">{{ $student->email }}</span>
                </p>
            </div>

            <!-- Tab Navigasi -->
            <div class="flex items-center bg-slate-100 p-1 rounded-lg border border-slate-200 self-start sm:self-auto">
                <button 
                    wire:click="$set('activeTab', 'book')" 
                    class="px-3.5 py-1.5 rounded-md text-xs font-semibold transition-all {{ $activeTab === 'book' ? 'bg-white text-blue-700 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Pesan Jadwal
                </button>
                <button 
                    wire:click="$set('activeTab', 'my_bookings')" 
                    class="px-3.5 py-1.5 rounded-md text-xs font-semibold transition-all {{ $activeTab === 'my_bookings' ? 'bg-white text-blue-700 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Jadwal Saya ({{ $myBookings->where('status', 'confirmed')->count() }})
                </button>
            </div>
        </div>
    </div>

    @if($activeTab === 'book')
        @if($pairs->isEmpty())
            <div class="bg-white rounded-xl border border-dashed border-slate-300 p-12 text-center">
                <div class="w-12 h-12 rounded-full bg-amber-50 text-amber-600 mx-auto flex items-center justify-center font-bold text-xl mb-3">
                    !
                </div>
                <h3 class="text-sm font-bold text-slate-900">Belum Ada Tentor yang Dipasangkan</h3>
                <p class="text-xs text-slate-500 max-w-sm mx-auto mt-1">
                    Akun Anda belum dipasangkan dengan tentor pembimbing oleh koordinator akademik. Silakan hubungi bagian Tata Usaha / Kurikulum sekolah.
                </p>
            </div>
        @else
            <!-- 1. Pilih Tentor & Mata Pelajaran -->
            <div class="mb-6">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    1. Pilih Tentor & Mata Pelajaran
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($pairs as $pair)
                        <button 
                            type="button"
                            wire:click="selectPair({{ $pair->id }})"
                            class="p-4 rounded-xl border text-left transition-all relative overflow-hidden group
                            {{ $selectedPairId === $pair->id 
                                ? 'bg-blue-50/70 border-blue-600 shadow-xs ring-1 ring-blue-600' 
                                : 'bg-white border-slate-200 hover:border-slate-300 hover:bg-slate-50/60' }}">
                            
                            @if($selectedPairId === $pair->id)
                                <div class="absolute top-2 right-2 text-blue-600 font-bold text-xs">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                </div>
                            @endif

                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold tracking-wide uppercase mb-1
                                {{ $selectedPairId === $pair->id ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-600' }}">
                                {{ $pair->subject->name ?? 'Private Khusus' }}
                            </span>
                            <div class="text-sm font-bold text-slate-900 group-hover:text-blue-700">
                                {{ $pair->teacher->name }}
                            </div>
                            <div class="text-[11px] text-slate-500 mt-0.5">
                                Tentor Pembimbing
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- 2. Pilih Hari (Horizontal Slider Mobile Friendly) -->
            <div class="mb-6">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        2. Pilih Hari & Tanggal
                    </label>
                    <span class="text-[11px] text-slate-400">10 Hari ke Depan</span>
                </div>
                
                <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-none -mx-4 px-4 sm:mx-0 sm:px-0">
                    @foreach($days as $day)
                        <button 
                            type="button"
                            wire:click="selectDate('{{ $day['date'] }}')"
                            class="shrink-0 w-20 py-3 px-2 rounded-xl border text-center transition-all
                            {{ $selectedDate === $day['date'] 
                                ? 'bg-slate-900 border-slate-900 text-white shadow-xs' 
                                : 'bg-white border-slate-200 text-slate-700 hover:border-slate-300 hover:bg-slate-50' }}">
                            <div class="text-[10px] font-semibold uppercase tracking-wider {{ $selectedDate === $day['date'] ? 'text-amber-400' : 'text-slate-400' }}">
                                {{ $day['day_short'] }}
                            </div>
                            <div class="text-lg font-bold mt-0.5 {{ $selectedDate === $day['date'] ? 'text-white' : 'text-slate-900' }}">
                                {{ $day['day_num'] }}
                            </div>
                            <div class="text-[10px] font-medium {{ $selectedDate === $day['date'] ? 'text-slate-300' : 'text-slate-500' }}">
                                {{ $day['month'] }}
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- 3. Daftar Slot Waktu -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        3. Slot Waktu Tersedia
                    </label>
                    <div class="flex items-center gap-3 text-[11px] text-slate-500">
                        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Tersedia</span>
                        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-slate-300"></span> Terisi</span>
                    </div>
                </div>

                @if(empty($slots))
                    <div class="bg-white rounded-xl border border-slate-200 p-8 text-center text-xs text-slate-500">
                        <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        Tidak ada slot bimbingan yang dibuka tentor pada hari ini. Silakan pilih hari lain.
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($slots as $slot)
                            @if($slot['is_booked'])
                                <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/80 opacity-60 cursor-not-allowed">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-slate-500">{{ $slot['start_time'] }} - {{ $slot['end_time'] }} WIB</span>
                                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-slate-200 text-slate-600">
                                            Terbooking
                                        </span>
                                    </div>
                                    <div class="text-[11px] text-slate-400 mt-2">{{ $slot['room'] }}</div>
                                </div>
                            @else
                                <button 
                                    type="button"
                                    wire:click="openConfirmModal({{ $slot['id'] }})"
                                    class="p-4 rounded-xl border border-emerald-300 bg-emerald-50/30 hover:bg-emerald-50 hover:border-emerald-500 text-left transition-all shadow-xs group">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-slate-900 group-hover:text-emerald-700">
                                            {{ $slot['start_time'] }} - {{ $slot['end_time'] }} WIB
                                        </span>
                                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-emerald-100 text-emerald-800">
                                            Tersedia
                                        </span>
                                    </div>
                                    <div class="text-[11px] text-slate-600 mt-2 flex items-center justify-between">
                                        <span>{{ $slot['room'] }}</span>
                                        <span class="font-bold text-emerald-700 text-xs">&rarr; Book</span>
                                    </div>
                                </button>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

    @else
        <!-- Tab: Jadwal Bimbingan Saya -->
        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
            <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Riwayat & Tiket Bimbingan</h3>
                    <p class="text-xs text-slate-500">Daftar sesi belajar private yang pernah dan akan Anda ikuti.</p>
                </div>
            </div>

            @if($myBookings->isEmpty())
                <div class="p-12 text-center text-xs text-slate-400">
                    Belum ada jadwal bimbingan yang pernah Anda pesan.
                </div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach($myBookings as $booking)
                        <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50/50 transition-colors">
                            <div class="flex items-start gap-4">
                                <div class="w-11 h-11 rounded-lg bg-blue-50 border border-blue-200 text-blue-700 flex flex-col items-center justify-center font-bold shrink-0">
                                    <span class="text-[10px] uppercase font-semibold leading-tight">{{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('M') }}</span>
                                    <span class="text-base leading-tight">{{ \Carbon\Carbon::parse($booking->booking_date)->format('d') }}</span>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-slate-900">{{ $booking->teacher->name }}</span>
                                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded bg-amber-50 text-amber-700 border border-amber-200">
                                            {{ $booking->subject->name ?? 'Bimbingan' }}
                                        </span>
                                    </div>
                                    <div class="text-xs text-slate-600 mt-1">
                                        {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('l, d F Y') }} &bull; 
                                        <span class="font-semibold text-slate-800">{{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }} WIB</span>
                                    </div>
                                    @if($booking->student_notes)
                                        <div class="text-[11px] text-slate-500 italic mt-1">
                                            Catatan: "{{ $booking->student_notes }}"
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center gap-3 self-end sm:self-auto">
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                                    {{ $booking->status === 'confirmed' ? 'bg-blue-100 text-blue-800' : ($booking->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600') }}">
                                    {{ $booking->status === 'confirmed' ? 'Terkonfirmasi' : ($booking->status === 'completed' ? 'Selesai' : 'Dibatalkan') }}
                                </span>

                                @if($booking->status === 'confirmed')
                                    <button 
                                        wire:click="openCancelModal({{ $booking->id }})"
                                        class="text-xs text-red-600 hover:text-red-800 font-semibold px-2.5 py-1 rounded border border-red-200 hover:bg-red-50 transition-colors">
                                        Batalkan
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    <!-- MODAL KONFIRMASI BOOKING -->
    @if($showConfirmModal && $currentPair && $selectedSlotObj)
        <div class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-slate-200 animate-in fade-in zoom-in duration-150">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900">Konfirmasi Pemesanan Jadwal</h3>
                    <button wire:click="closeConfirmModal" class="text-slate-400 hover:text-slate-600 text-lg">&times;</button>
                </div>

                <div class="mt-4 space-y-3 bg-slate-50 p-4 rounded-xl border border-slate-200 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Mata Pelajaran:</span>
                        <span class="font-bold text-slate-800">{{ $currentPair->subject->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Tentor Pembimbing:</span>
                        <span class="font-bold text-slate-800">{{ $currentPair->teacher->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Hari & Tanggal:</span>
                        <span class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d F Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Waktu:</span>
                        <span class="font-bold text-blue-700">{{ substr($selectedSlotObj->start_time, 0, 5) }} - {{ substr($selectedSlotObj->end_time, 0, 5) }} WIB</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Lokasi:</span>
                        <span class="font-bold text-slate-800">{{ $selectedSlotObj->room_or_location ?? 'Ruang Diskusi' }}</span>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">
                        Catatan Topik / Materi yang Ingin Dibahas (Opsional):
                    </label>
                    <textarea 
                        wire:model="studentNotes"
                        placeholder="Contoh: Mau bahas soal try out nomor 10-25 bab ikatan kimia..."
                        rows="2"
                        class="w-full text-xs p-3 rounded-lg border border-slate-300 focus:outline-hidden focus:border-blue-600 focus:ring-1 focus:ring-blue-600"></textarea>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <button 
                        type="button" 
                        wire:click="closeConfirmModal"
                        class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                        Batal
                    </button>
                    <button 
                        type="button" 
                        wire:click="confirmBooking"
                        wire:loading.attr="disabled"
                        class="px-5 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-xs transition-colors flex items-center gap-2">
                        <span wire:loading.remove>Konfirmasi & Book Sekarang</span>
                        <span wire:loading>Memproses...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- MODAL BATALKAN BOOKING -->
    @if($showCancelModal)
        <div class="fixed inset-0 z-50 bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-xl border border-slate-200">
                <h3 class="text-sm font-bold text-slate-900">Batalkan Jadwal Bimbingan?</h3>
                <p class="text-xs text-slate-500 mt-2">
                    Slot waktu ini akan dibuka kembali agar dapat digunakan oleh siswa lain. Apakah Anda yakin?
                </p>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <button 
                        type="button" 
                        wire:click="closeCancelModal"
                        class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 rounded-lg">
                        Kembali
                    </button>
                    <button 
                        type="button" 
                        wire:click="confirmCancel"
                        class="px-4 py-2 text-xs font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg shadow-xs">
                        Ya, Batalkan Sesi
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
