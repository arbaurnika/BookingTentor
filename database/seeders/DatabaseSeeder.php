<?php

namespace Database\Seeders;

use App\Models\AvailabilitySlot;
use App\Models\Booking;
use App\Models\StudentTeacherPair;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $defaultPassword = Hash::make('password');

        // 1. Admin Akun
        $admin = User::firstOrCreate(
            ['email' => 'admin@globaldarussalam.sch.id'],
            [
                'name' => 'Administrator GDA',
                'role' => 'admin',
                'password' => $defaultPassword,
                'phone' => '081234567890',
                'nisn_or_nip' => 'ADM-001',
                'is_active' => true,
            ]
        );

        // 2. Mata Pelajaran
        $matematika = Subject::firstOrCreate(['code' => 'MTK'], [
            'name' => 'Matematika Peminatan',
            'description' => 'Bimbingan intensif kalkulus, aljabar, dan persiapan UTBK SNBT.',
        ]);

        $kimia = Subject::firstOrCreate(['code' => 'KIM'], [
            'name' => 'Kimia',
            'description' => 'Pendalaman materi stoikiometri, ikatan kimia, dan kimia organik.',
        ]);

        $inggris = Subject::firstOrCreate(['code' => 'ING'], [
            'name' => 'Bahasa Inggris',
            'description' => 'TOEFL Preparation & Academic English speaking & writing.',
        ]);

        // 3. Guru / Tentor (1 Tentor membimbing beberapa siswa)
        $teacherJoko = User::firstOrCreate(
            ['email' => 'joko.prasetyo@globaldarussalam.sch.id'],
            [
                'name' => 'Joko Prasetyo, S.Pd.',
                'role' => 'teacher',
                'password' => $defaultPassword,
                'phone' => '081211112222',
                'nisn_or_nip' => '198501012010011001',
                'is_active' => true,
            ]
        );

        $teacherSiti = User::firstOrCreate(
            ['email' => 'siti.aminah@globaldarussalam.sch.id'],
            [
                'name' => 'Siti Aminah, M.Sc.',
                'role' => 'teacher',
                'password' => $defaultPassword,
                'phone' => '081233334444',
                'nisn_or_nip' => '198902022014022002',
                'is_active' => true,
            ]
        );

        $teacherAhmad = User::firstOrCreate(
            ['email' => 'ahmad.fauzi@globaldarussalam.sch.id'],
            [
                'name' => 'Ahmad Fauzi, S.Pd.',
                'role' => 'teacher',
                'password' => $defaultPassword,
                'phone' => '081255556666',
                'nisn_or_nip' => '199103032016031003',
                'is_active' => true,
            ]
        );

        // 4. Siswa
        $studentBudi = User::firstOrCreate(
            ['email' => 'budi.santoso@globaldarussalam.sch.id'],
            [
                'name' => 'Budi Santoso',
                'role' => 'student',
                'password' => $defaultPassword,
                'phone' => '089800010001',
                'nisn_or_nip' => '0061234561', // NISN
                'is_active' => true,
            ]
        );

        $studentAnnisa = User::firstOrCreate(
            ['email' => 'annisa.rahma@globaldarussalam.sch.id'],
            [
                'name' => 'Annisa Rahma',
                'role' => 'student',
                'password' => $defaultPassword,
                'phone' => '089800020002',
                'nisn_or_nip' => '0061234562',
                'is_active' => true,
            ]
        );

        $studentDimas = User::firstOrCreate(
            ['email' => 'dimas.arya@globaldarussalam.sch.id'],
            [
                'name' => 'Dimas Arya',
                'role' => 'student',
                'password' => $defaultPassword,
                'phone' => '089800030003',
                'nisn_or_nip' => '0061234563',
                'is_active' => true,
            ]
        );

        $studentZahra = User::firstOrCreate(
            ['email' => 'zahra.amalia@globaldarussalam.sch.id'],
            [
                'name' => 'Zahra Amalia',
                'role' => 'student',
                'password' => $defaultPassword,
                'phone' => '089800040004',
                'nisn_or_nip' => '0051234564',
                'is_active' => true,
            ]
        );

        // 5. Pairing Siswa <-> Tentor
        // Pak Joko membimbing Budi, Annisa, dan Dimas di Matematika
        StudentTeacherPair::firstOrCreate([
            'student_id' => $studentBudi->id,
            'teacher_id' => $teacherJoko->id,
            'subject_id' => $matematika->id,
        ]);
        StudentTeacherPair::firstOrCreate([
            'student_id' => $studentAnnisa->id,
            'teacher_id' => $teacherJoko->id,
            'subject_id' => $matematika->id,
        ]);
        StudentTeacherPair::firstOrCreate([
            'student_id' => $studentDimas->id,
            'teacher_id' => $teacherJoko->id,
            'subject_id' => $matematika->id,
        ]);

        // Bu Siti membimbing Dimas & Zahra di Kimia
        StudentTeacherPair::firstOrCreate([
            'student_id' => $studentDimas->id,
            'teacher_id' => $teacherSiti->id,
            'subject_id' => $kimia->id,
        ]);
        StudentTeacherPair::firstOrCreate([
            'student_id' => $studentZahra->id,
            'teacher_id' => $teacherSiti->id,
            'subject_id' => $kimia->id,
        ]);

        // Pak Ahmad membimbing Budi di Bahasa Inggris
        StudentTeacherPair::firstOrCreate([
            'student_id' => $studentBudi->id,
            'teacher_id' => $teacherAhmad->id,
            'subject_id' => $inggris->id,
        ]);

        // 6. Slot Ketersediaan Guru (Contoh Jadwal Mingguan)
        // Pak Joko: Senin & Selasa sore
        $slotJokoSenin1 = AvailabilitySlot::firstOrCreate([
            'teacher_id' => $teacherJoko->id,
            'day_of_week' => 'Monday',
            'start_time' => '15:30:00',
            'end_time' => '16:30:00',
            'room_or_location' => 'Ruang Diskusi Lt. 2',
        ]);

        $slotJokoSenin2 = AvailabilitySlot::firstOrCreate([
            'teacher_id' => $teacherJoko->id,
            'day_of_week' => 'Monday',
            'start_time' => '16:30:00',
            'end_time' => '17:30:00',
            'room_or_location' => 'Ruang Diskusi Lt. 2',
        ]);

        $slotJokoSelasa = AvailabilitySlot::firstOrCreate([
            'teacher_id' => $teacherJoko->id,
            'day_of_week' => 'Tuesday',
            'start_time' => '15:30:00',
            'end_time' => '16:30:00',
            'room_or_location' => 'Lab Komputer 1',
        ]);

        // Bu Siti: Rabu & Kamis sore
        AvailabilitySlot::firstOrCreate([
            'teacher_id' => $teacherSiti->id,
            'day_of_week' => 'Wednesday',
            'start_time' => '15:30:00',
            'end_time' => '16:30:00',
            'room_or_location' => 'Lab Kimia',
        ]);

        // 7. Contoh Booking yang sudah ada (Sample)
        Booking::firstOrCreate([
            'student_id' => $studentBudi->id,
            'teacher_id' => $teacherJoko->id,
            'subject_id' => $matematika->id,
            'slot_id' => $slotJokoSenin1->id,
            'booking_date' => now()->next('Monday')->format('Y-m-d'),
            'start_time' => '15:30:00',
            'end_time' => '16:30:00',
            'status' => 'confirmed',
            'student_notes' => 'Mau bahas soal latihan integral substitusi & parsial bab 3.',
        ]);
    }
}
