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

class AlgoritmaController extends Controller
{
    public function generateSchedule(Request $request)
    {
        $maxIterations = 10; // Jumlah iterasi yang diinginkan
        $employedBees = 10;   // Jumlah lebah pekerja
        $onlookerBees = 5;    // Jumlah lebah pengamat
        $maxChange = 2;       // Batasan maksimum perubahan dalam setiap iterasi

        // Inisialisasi jadwal pelajaran secara acak (bisa diatur sesuai kebutuhan)
        $initialSchedule = $this->initializeSchedule($request->id);

        // Hitung nilai fitness untuk jadwal awal
        $bestSchedule = $initialSchedule;
        $bestFitness = $this->fitness($bestSchedule);

        // Inisialisasi daftar employed bees dan onlooker bees
        $employedBeesList = [];
        $onlookerBeesList = [];

        for ($i = 0; $i < $employedBees; $i++) {
            $initialSchedule = $this->initializeSchedule($request->id);
            if ($initialSchedule) {
                $employedBeesList[] = $initialSchedule;
            }
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
            // dd($employedBeesList);

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
            
        //    echo "Iteration: $iteration, Constrains: $bestFitness<br>" . PHP_EOL;
        }

        // Simpan jadwal pelajaran ke dalam database (misalnya, dalam tabel jadwal_pelajaran)
        $day_id = [];
        // foreach ($bestSchedule as $schedule) {
        //     if(in_array($schedule['day'], $day_id)) {

        //     }
        //     else {
        //         $day_id[] = $schedule['day'];
        //         echo "<hr><br>";
        //     }
        //     echo "Day: " . Day::find($schedule['day'])->name . "<br>";
        //     echo "Time: " . Time::find($schedule['time'])->range_min . "<br>";
        //     echo "Lesson: " . Lesson::find($schedule['lesson'])->name . "<br>";
        //     echo "Room: " . Room::find($schedule['room'])->name . "<br>";
        //     echo "Teacher: " . Teacher::find($schedule['teacher'])->name . "<br><br><br>";
        // }
        // dd($bestSchedule);
        // Plotting::truncate();
        // foreach ($bestSchedule as $schedule) {
        //     Plotting::create([
        //         'day_id' => $schedule['day'],
        //         'time_id' => $schedule['time'],
        //         // 'mata_pelajaran' => $schedule['mata_pelajaran'],
        //         'code_room_id' => $schedule['room'],
        //     ]);
        // }
        return Inertia::render('Plotting/GeneratePlotting/Index', [
            'teachers' => Teacher::all(),
            'majors' => Major::all(),
            'rooms' => Room::find($request->id),
            'lessons' => Lesson::all(),
            'days' => Day::all(),
            'times' => Time::all(),
            'schedule' => $bestSchedule
          ]);

        // return response()->json(['message' => 'Jadwal Pelajaran telah digenerate']);
    }

    private function initializeSchedule($id_room)
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
            $rooms[] = $room['id'];
        }
        $teachers = [];
        foreach (Teacher::select('id')->get()->toArray() as $teacher) {
            $teachers[] = $teacher['id'];
        }
        $schedule = [];

        // dd($this->getSchedule($schedule, $days, $times, $teachers, $rooms, $lessons));
        // dd($times);
        // $kali = count($times) * count($lessons);
        // $kali = count($times) * count($lessons) * count($rooms) * count($teachers);
        // dd($kali);
        // foreach ($rooms as $room) {
        // mengambil data jadwal untuk 1 minggu
        // foreach($rooms as $room) {
        foreach ($days as $day) {
            foreach ($times as $time) {
                $plotting = Plotting::where('day_id', $day)->where('time_id', $time)->where('code_room_id', $id_room)->get();

                $getPlotting = $plotting->map(fn($p) => $p->id)->toArray();

                $getRandPlotting = Plotting::find($getPlotting[array_rand($getPlotting)]);

                if ($getRandPlotting) {
                    $schedule[] = [
                        'day' => $day,
                        'time' => $time,
                        'lesson' => $getRandPlotting->lesson_id,
                        'room' => $getRandPlotting->code_room_id,
                        'teacher' => $getRandPlotting->teacher_id, // Misalnya, kita asumsikan semua pelajaran berlangsung di ruangan A101
                        // Misalnya, kita asumsikan semua pelajaran berlangsung di ruangan A101
                    ];
                }
                //  else {
                // }
            }
            // }
            // }
            // }
        }

        // }
        // }

        // $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        // $hours = ['08:00', '09:00', '10:00', '11:00', '13:00', '14:00', '15:00', '16:00'];
        // $subjects = ['Matematika', 'Bahasa Inggris', 'Fisika', 'Kimia', 'Biologi'];

        // $schedule = [];

        // foreach ($days as $day) {
        //     foreach ($hours as $hour) {
        //         $schedule[] = [
        //             'hari' => $day,
        //             'jam' => $hour,
        //             'mata_pelajaran' => $subjects[array_rand($subjects)],
        //             'ruangan' => 'A101', // Misalnya, kita asumsikan semua pelajaran berlangsung di ruangan A101
        //         ];
        //     }
        // }

        // dd($schedule);

        // $getPlotting = Plotting::all();

        // foreach($getPlotting as $plotting) {
        //     $schedule[] = [
        //         'day' => $plotting->day_id,
        //         'time' => $plotting->time_id,
        //         'lesson' => $plotting->lesson_id,
        //         'room' => $plotting->code_room_id,
        //         'teacher' => $plotting->teacher_id, // Misalnya, kita asumsikan semua pelajaran berlangsung di ruangan A101
        //         // Misalnya, kita asumsikan semua pelajaran berlangsung di ruangan A101
        //     ];
        // }
        return $schedule;
    }

    private function generateNeighborSchedule($currentSchedule, $maxChange)
    {
        // Implementasi pembangkitan tetangga untuk jadwal pelajaran
        // Di sini, Anda bisa melakukan perubahan pada jadwal yang ada, misalnya menggeser waktu pelajaran atau menukar mata pelajaran pada waktu tertentu

        // Contoh sederhana: Misalnya, kita akan mengacak jadwal pelajaran yang diberikan
        $newSchedule = $currentSchedule;
        // dd($newSchedule);
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
            $key = $lesson['day'] . $lesson['time'] . $lesson['room'];

            if (isset($checkConflict[$key])) {
                $conflictCount++;
            } else {
                $checkConflict[$key] = true;
            }
        }

        return $conflictCount;
    }
}