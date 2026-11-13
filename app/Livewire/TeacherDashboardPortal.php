<?php

namespace App\Livewire;

use App\Models\Booking;
use App\Models\StudentTeacherPair;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TeacherDashboardPortal extends Component
{
    public $completingBookingId = null;

    public $teacherFeedback = '';

    public $showCompleteModal = false;

    public $feedbackMessage = null;

    public function openCompleteModal(int $bookingId): void
    {
        $this->completingBookingId = $bookingId;
        $this->teacherFeedback = '';
        $this->showCompleteModal = true;
    }

    public function closeCompleteModal(): void
    {
        $this->showCompleteModal = false;
        $this->completingBookingId = null;
        $this->teacherFeedback = '';
    }

    public function markCompleted(): void
    {
        $booking = Booking::where('teacher_id', Auth::id())
            ->where('id', $this->completingBookingId)
            ->first();

        if ($booking) {
            $booking->update([
                'status' => 'completed',
                'teacher_feedback' => $this->teacherFeedback,
            ]);

            $this->feedbackMessage = 'Sesi bimbingan berhasil ditandai selesai!';
        }

        $this->closeCompleteModal();
    }

    public function render()
    {
        $teacher = Auth::user();

        // 1. Booking mendatang
        $upcomingBookings = Booking::with(['student', 'subject', 'slot'])
            ->where('teacher_id', $teacher->id)
            ->where('status', 'confirmed')
            ->where('booking_date', '>=', Carbon::today()->format('Y-m-d'))
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->get();

        // 2. Riwayat bimbingan selesai
        $completedBookings = Booking::with(['student', 'subject', 'slot'])
            ->where('teacher_id', $teacher->id)
            ->whereIn('status', ['completed', 'cancelled'])
            ->orderBy('booking_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->take(15)
            ->get();

        // 3. Siswa bimbingan yang dipasangkan ke guru ini
        $assignedStudents = StudentTeacherPair::with(['student', 'subject'])
            ->where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->get();

        return view('livewire.teacher-dashboard-portal', [
            'teacher' => $teacher,
            'upcomingBookings' => $upcomingBookings,
            'completedBookings' => $completedBookings,
            'assignedStudents' => $assignedStudents,
        ]);
    }
}
