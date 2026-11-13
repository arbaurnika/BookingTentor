<?php

namespace App\Notifications;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBookingNotification extends Notification
{
    use Queueable;

    public function __construct(public Booking $booking) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $studentName = $this->booking->student->name ?? 'Siswa';
        $subjectName = $this->booking->subject->name ?? 'Bimbingan Belajar';
        $dateFormatted = Carbon::parse($this->booking->booking_date)->translatedFormat('l, d F Y');
        $timeFormatted = substr($this->booking->start_time, 0, 5).' - '.substr($this->booking->end_time, 0, 5).' WIB';
        $room = $this->booking->slot->room_or_location ?? 'Ruang Diskusi / Lab';
        $notes = $this->booking->student_notes ?: 'Tidak ada catatan tambahan.';

        // Buat link Google Calendar
        $startIso = Carbon::parse($this->booking->booking_date.' '.$this->booking->start_time)->format('Ymd\THis');
        $endIso = Carbon::parse($this->booking->booking_date.' '.$this->booking->end_time)->format('Ymd\THis');
        $calendarUrl = 'https://calendar.google.com/calendar/render?action=TEMPLATE'
            .'&text='.urlencode("Private {$subjectName} - {$studentName}")
            ."&dates={$startIso}/{$endIso}"
            .'&details='.urlencode("Bimbingan belajar siswa: {$studentName}\nMateri/Catatan: {$notes}")
            .'&location='.urlencode($room);

        return (new MailMessage)
            ->subject("[GDA Booking] Jadwal Bimbingan Baru dari {$studentName}")
            ->greeting("Halo {$notifiable->name},")
            ->line('Ada siswa bimbingan Anda yang telah memesan jadwal private belajar:')
            ->line("**Siswa:** {$studentName}")
            ->line("**Mata Pelajaran:** {$subjectName}")
            ->line("**Hari/Tanggal:** {$dateFormatted}")
            ->line("**Waktu:** {$timeFormatted}")
            ->line("**Lokasi:** {$room}")
            ->line("**Catatan Materi:** \"{$notes}\"")
            ->action('Tambahkan ke Google Calendar', $calendarUrl)
            ->line('Silakan login ke portal aplikasi untuk melihat rekapitulasi jadwal Anda secara lengkap.');
    }
}
