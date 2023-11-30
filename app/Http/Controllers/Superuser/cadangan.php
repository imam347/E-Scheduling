<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\DataTableRequest;
use App\Models\Plotting;
use App\Models\Major;
use App\Models\Room;
use App\Models\Lesson;
use App\Models\Teacher;
use App\Models\Day;
use App\Models\Time;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;

class AlgoritmaController2 extends Controller
{
    public function generateSchedule()
    {
        $maxIterations = 100; // Jumlah iterasi yang diinginkan
        $employedBees = 10;   // Jumlah lebah pekerja
        $onlookerBees = 5;    // Jumlah lebah pengamat
        $maxChange = 2;       // Batasan maksimum perubahan dalam setiap iterasi

        // Inisialisasi jadwal pelajaran secara acak (bisa diatur sesuai kebutuhan)
        $initialSchedule = $this->initializeSchedule();

        // Hitung nilai fitness untuk jadwal awal
        $bestSchedule = $initialSchedule;
        $bestFitness = $this->fitness($bestSchedule);

        // Inisialisasi daftar employed bees dan onlooker bees
        $employedBeesList = [];
        $onlookerBeesList = [];

        for ($i = 0; $i < $employedBees; $i++) {
            $employedBeesList[] = $this->initializeSchedule();
        }

        // Mulai iterasi untuk mencari solusi terbaik
        for ($iteration = 1; $iteration <= $maxIterations; $iteration++) {
            // Proses employed bees
            foreach ($employedBeesList as $key => $employedBee) {
                $newSchedule = $this->generateNeighborSchedule($employedBee, $maxChange);
                $newFitness = $this->fitness($newSchedule);

                if ($newFitness < $this->fitness($employedBee)) {
                    $employedBeesList[$key] = $newSchedule;
                }
            }

            // Tentukan employed bee dengan fitness terbaik untuk dijadikan onlooker bee
            $bestEmployedBee = $employedBeesList[array_search(min(array_map([$this, 'fitness'], $employedBeesList)), $employedBeesList)];

            // Proses onlooker bees
            for ($i = 0; $i < $onlookerBees; $i++) {
                $newSchedule = $this->generateNeighborSchedule($bestEmployedBee, $maxChange);
                $newFitness = $this->fitness($newSchedule);

                if ($newFitness < $this->fitness($bestSchedule)) {
                    $bestSchedule = $newSchedule;
                    $bestFitness = $newFitness;
                }
            }

            // Tampilkan status iterasi dan solusi terbaik
            echo "Iteration: $iteration, Best Fitness: $bestFitness<br>" . PHP_EOL;
        }

        // Simpan jadwal pelajaran ke dalam database (misalnya, dalam tabel jadwal_pelajaran)
        dd($bestSchedule);
        // Plotting::truncate();
        foreach ($bestSchedule as $schedule) {
            Plotting::create([
                'day_id' => $schedule['day'],
                'time_id' => $schedule['time'],
                // 'mata_pelajaran' => $schedule['mata_pelajaran'],
                'code_room_id' => $schedule['room'],
            ]);
        }

        // return response()->json(['message' => 'Jadwal Pelajaran telah digenerate']);
    }

    private function initializeSchedule()
    {
        // Implementasi inisialisasi jadwal pelajaran secara acak sesuai kebutuhan
        // Contoh sederhana: Misalnya, kita memiliki 5 hari dalam seminggu, 8 jam pelajaran, dan beberapa mata pelajaran
        $days = [];
        foreach (Day::select('id')->get()->toArray() as $day) {
            $days[] = $day['id'];
        }
        $times = [];
        foreach (Time::select('id')->get()->toArray() as $time) {
            $times[] = $time['id'];
        }
        $lessons = [];
        foreach (Lesson::select('id')->get()->toArray() as $lesson) {
            $lessons[] = $lesson['id'];
        }
        $rooms = [];
        foreach (Room::select('id')->get()->toArray() as $room) {
            $rooms[] = $time['id'];
        }
        $schedule = [];
        // dd($times);
        foreach ($days as $day) {
            foreach ($times as $time) {
                $schedule[] = [
                    'day' => $day,
                    'time' => $times[array_rand($times)],
                    'lesson' => $lessons[array_rand($lessons)],
                    'room' => $rooms, // Misalnya, kita asumsikan semua pelajaran berlangsung di ruangan A101
                ];
            }
        }

        dd($schedule);

        return $schedule;
    }

    private function generateNeighborSchedule($currentSchedule, $maxChange)
    {
        // Implementasi pembangkitan tetangga untuk jadwal pelajaran
        // Di sini, Anda bisa melakukan perubahan pada jadwal yang ada, misalnya menggeser waktu pelajaran atau menukar mata pelajaran pada waktu tertentu

        // Contoh sederhana: Misalnya, kita akan mengacak jadwal pelajaran yang diberikan
        $newSchedule = $currentSchedule;
        $randomIndex1 = array_rand($newSchedule);
        $randomIndex2 = array_rand($newSchedule);

        $temp = $newSchedule[$randomIndex1];
        $newSchedule[$randomIndex1] = $newSchedule[$randomIndex2];
        $newSchedule[$randomIndex2] = $temp;

        return $newSchedule;
    }

    private function fitness($schedule)
    {
        // Implementasi fungsi fitness untuk menilai kualitas jadwal pelajaran
        // Di sini, Anda bisa membuat fungsi yang menghitung penalti untuk jadwal yang tidak valid atau menghitung skor berdasarkan kriteria tertentu.

        // Contoh sederhana: Misalnya, kita akan menghitung jumlah mata pelajaran yang bentrok (satu mata pelajaran diajar pada waktu yang sama)
        $conflictCount = 0;
        $checkConflict = [];
        // dd($schedule[0]);/
        foreach ($schedule as $lesson) {
            $key = $lesson['day'] . $lesson['time'];

            if (isset($checkConflict[$key])) {
                $conflictCount++;
            } else {
                $checkConflict[$key] = true;
            }
        }

        return $conflictCount;
    }
}
