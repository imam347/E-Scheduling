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
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;

class GeneratePlottingController extends Controller
{
  public function index() {
    $major = Major::all();
    return Inertia::render('Plotting/GeneratePlotting/Index', [
      'teachers' => Teacher::all(),
      'majors' => Major::all(),
      'rooms' => Room::all(),
      'lessons' => Lesson::all(),
      'days' => Day::all(),
    ]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function save(Request $request)
  {
    $request->validate([
      'schedule' => 'required',
      // 'major' => 'nullable|integer:majors',
    ]);
    //     $major = Major::find($request->major_id);

    // if (!$major) {
    //     return redirect()->back()->with('error', __('Jurusan tidak ditemukan'));
    // }     

    if ($request->schedule) {
      Result::where('code_room_id', $request->schedule[0]['room'])->delete();
      foreach($request->schedule as $data) {
        Plotting::where('day_id', $data['day'])->where('time_id', $data['time'])->where('teacher_id', $data['teacher'])->delete();

        $resullt = [
          'code_room_id' => $data['room'],
          'lesson_id' => $data['lesson'],
          'teacher_id' => $data['teacher'],
          'day_id' => $data['day'],
          'time_id' => $data['time']
        ];

        Result::create($resullt);
      }
      return redirect('/plotting')->with('success', __(
        'Jadwal Berhasil Disimpan',
      ));
    }
    return redirect()->back()->with('error', __(
      'Jadwal tidak dapat disimpan'
    ));
  }
}
