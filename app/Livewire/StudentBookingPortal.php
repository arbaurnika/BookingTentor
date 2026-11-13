<?php

namespace App\Livewire;

use App\Models\AvailabilitySlot;
use App\Models\Booking;
use App\Models\StudentTeacherPair;
use App\Notifications\NewBookingNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class StudentBookingPortal extends Component
{
    public $selectedPairId = null;

    public $selectedDate;

    public $selectedSlotId = null;

    public $studentNotes = '';

    public $showConfirmModal = false;

    public $activeTab = 'book'; // 'book' or 'my_bookings'

    public $cancellingBookingId = null;

    public $showCancelModal = false;

    public $feedbackMessage = null;

    public $feedbackType = 'success'; // success or error

    public function mount(): void
    {
        $this->selectedDate = Carbon::now()->addDay()->format('Y-m-d');

        $firstPair = StudentTeacherPair::where('student_id', Auth::id())
            ->where('is_active', true)
            ->first();

        if ($firstPair) {
            $this->selectedPairId = $firstPair->id;
        }
    }

    public function selectPair(int $pairId): void
    {
        $this->selectedPairId = $pairId;
        $this->selectedSlotId = null;
        $this->showConfirmModal = false;
    }

    public function selectDate(string $date): void
    {
        $this->selectedDate = $date;
        $this->selectedSlotId = null;
        $this->showConfirmModal = false;
    }

    public function openConfirmModal(int $slotId): void
    {
        $this->selectedSlotId = $slotId;
        $this->studentNotes = '';
        $this->showConfirmModal = true;
    }

    public function closeConfirmModal(): void
    {
        $this->showConfirmModal = false;
        $this->selectedSlotId = null;
        $this->studentNotes = '';
    }

    public function confirmBooking(): void
    {
        $student = Auth::user();
        $pair = StudentTeacherPair::with(['teacher', 'subject'])->find($this->selectedPairId);
        $slot = AvailabilitySlot::find($this->selectedSlotId);

        if (! $pair || ! $slot) {
            $this->feedbackMessage = 'Data bimbingan atau slot waktu tidak valid.';
            $this->feedbackType = 'error';

            return;
        }

        // Cek bentrok (apakah slot di tanggal ini sudah dibooking orang lain)
        $isAlreadyBooked = Booking::where('teacher_id', $pair->teacher_id)
            ->where('booking_date', $this->selectedDate)
            ->where('start_time', $slot->start_time)
            ->where('status', 'confirmed')
            ->exists();

        if ($isAlreadyBooked) {
            $this->feedbackMessage = 'Maaf, slot waktu ini baru saja dipesan oleh siswa lain. Silakan pilih slot lain.';
            $this->feedbackType = 'error';
            $this->closeConfirmModal();

            return;
        }

        // Simpan booking
        $booking = Booking::create([
            'student_id' => $student->id,
            'teacher_id' => $pair->teacher_id,
            'subject_id' => $pair->subject_id,
            'slot_id' => $slot->id,
            'booking_date' => $this->selectedDate,
            'start_time' => $slot->start_time,
            'end_time' => $slot->end_time,
            'status' => 'confirmed',
            'student_notes' => $this->studentNotes,
        ]);

        // Kirim email notifikasi ke guru
        try {
            $pair->teacher->notify(new NewBookingNotification($booking));
        } catch (\Throwable $e) {
            // Email failure should not break booking flow
        }

        $this->closeConfirmModal();
        $this->feedbackMessage = 'Alhamdulillah! Jadwal bimbingan private berhasil dipesan.';
        $this->feedbackType = 'success';
        $this->activeTab = 'my_bookings';
    }

    public function openCancelModal(int $bookingId): void
    {
        $this->cancellingBookingId = $bookingId;
        $this->showCancelModal = true;
    }

    public function closeCancelModal(): void
    {
        $this->showCancelModal = false;
        $this->cancellingBookingId = null;
    }

    public function confirmCancel(): void
    {
        $booking = Booking::where('student_id', Auth::id())
            ->where('id', $this->cancellingBookingId)
            ->first();

        if ($booking) {
            $booking->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => 'Dibatalkan oleh siswa melalui portal.',
            ]);

            $this->feedbackMessage = 'Jadwal bimbingan berhasil dibatalkan.';
            $this->feedbackType = 'info';
        }

        $this->closeCancelModal();
    }

    public function render()
    {
        $student = Auth::user();

        // 1. Ambil seluruh tentor yang dipasangkan ke siswa ini
        $pairs = StudentTeacherPair::with(['teacher', 'subject'])
            ->where('student_id', $student->id)
            ->where('is_active', true)
            ->get();

        $currentPair = $pairs->firstWhere('id', $this->selectedPairId) ?? $pairs->first();

        // 2. Generate 10 hari ke depan
        $days = [];
        for ($i = 0; $i < 10; $i++) {
            $date = Carbon::now()->addDays($i);
            $days[] = [
                'date' => $date->format('Y-m-d'),
                'day_name' => $date->translatedFormat('l'),
                'day_short' => $date->translatedFormat('D'),
                'day_num' => $date->format('d'),
                'month' => $date->translatedFormat('M'),
                'day_of_week' => $date->format('l'), // English day name for slot matching
                'is_today' => $date->isToday(),
            ];
        }

        // 3. Ambil slot ketersediaan tentor terpilih pada hari terpilih
        $slots = [];
        if ($currentPair) {
            $carbonDate = Carbon::parse($this->selectedDate);
            $englishDay = $carbonDate->format('l');

            $allSlots = AvailabilitySlot::where('teacher_id', $currentPair->teacher_id)
                ->where('is_active', true)
                ->where(function ($query) use ($englishDay) {
                    $query->where('day_of_week', $englishDay)
                        ->orWhere('specific_date', $this->selectedDate);
                })
                ->orderBy('start_time')
                ->get();

            // Cek status booking untuk setiap slot
            foreach ($allSlots as $slot) {
                $isBooked = Booking::where('teacher_id', $currentPair->teacher_id)
                    ->where('booking_date', $this->selectedDate)
                    ->where('start_time', $slot->start_time)
                    ->where('status', 'confirmed')
                    ->exists();

                $slots[] = [
                    'id' => $slot->id,
                    'start_time' => substr($slot->start_time, 0, 5),
                    'end_time' => substr($slot->end_time, 0, 5),
                    'room' => $slot->room_or_location ?? 'Ruang Diskusi',
                    'is_booked' => $isBooked,
                ];
            }
        }

        // 4. Riwayat Booking Siswa
        $myBookings = Booking::with(['teacher', 'subject', 'slot'])
            ->where('student_id', $student->id)
            ->orderBy('booking_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        $selectedSlotObj = $this->selectedSlotId ? AvailabilitySlot::find($this->selectedSlotId) : null;

        return view('livewire.student-booking-portal', [
            'student' => $student,
            'pairs' => $pairs,
            'currentPair' => $currentPair,
            'days' => $days,
            'slots' => $slots,
            'myBookings' => $myBookings,
            'selectedSlotObj' => $selectedSlotObj,
        ]);
    }
}
