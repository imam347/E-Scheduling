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

class PlottingController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return Inertia::render('Plotting/Index', [
      'teachers' => Teacher::all(),
      'majors' => Major::all(),
      'rooms' => Room::all(),
      'lessons' => Lesson::all(),
      'days' => Day::all(),
      'schedule' => Result::all(),
    ]);
  }

  public function paginate(DataTableRequest $request)
  {
    $request->validated();
    return Result::where(function (Builder $query) use ($request) {
      $search = '%' . $request->search . '%';
    })
      ->where(function (Builder $query) use ($request) {
        // $query->whereRelation('room', 'name', $request->search);
        // $query->where(function($query) use ($request) {
        // $query->orWhereRelation('major', function($q) use ($request) {
        //   $q->where('name', $request->search);
        // });
        // $query->orWhereRelation('lesson', function($q) use ($request) {
        //   $q->where('name', $request->search);
        // });
        // $query->orWhereRelation('teacher', function($q) use ($request) {
        //   $q->where('name', $request->search);
        // });
        // $query->orWhereRelation('day', function($q) use ($request) {
        //   $q->where('name', $request->search);
        // });
        // $query->orWhereRelation('time', function($q) use ($request) {
        //   $q->where('range', $request->search);
        //   // $q->orWhere('sks', $request->search);
        // });
        // $query->orWhereRelation('time', function($q) use ($request) {
        //   // $q->where('range', $request->search);
        //   $q->orWhere('sks', $request->search);
        // });

        // });
      })
      // ->orderBy($request->input('order.key') ?: 'name', $request->input('order.dir') ?: 'asc')
      ->with('room.major', 'lesson', 'teacher', 'day', 'time')
      ->paginate($request->per_page ?: 10);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $post = $request->validate([
      'code_room_id' => 'required',
      'lesson_id' => 'required',
      'teacher_id' => 'required',
    ]);
    $lesson = Lesson::find($request->lesson_id);
    $post['total_sks'] = $lesson->sks;

    $checkPlotting = Plotting::where('code_room_id', $request->code_room_id)->where('lesson_id', $request->lesson_id)->where('teacher_id', $request->teacher_id)->get();

    if ($checkPlotting->count() < 1) {
      if ($plotting = Plotting::create($post)) {
        $teacher = Teacher::find($plotting->teacher_id);
        return redirect()->back()->with('success', __(
          'plotting mengajar guru `:teacher` has been created',
          [
            'teacher' => $teacher->name,
          ],
        ));
      }
      return redirect()->back()->with('error', __(
        'can\'t create plotting mengajar'
      ));
    }
    return redirect()->back()->with('error', __(
      'can\'t create plotting mengajar'
    ));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  // public function storePreferensi(Request $request)
  // {
  //   $post = $request->validate([
  //     'teacher_id' => 'required',
  //     'day_id' => 'required'
  //   ]);
  //   $checkPlotting = Plotting::where('code_room_id', $request->code_room_id)->where('lesson_id', $request->lesson_id)->where('teacher_id', $request->teacher_id)->get();

  //   $times = Time::all();

  //   $getPlottingTeacher = Plotting::where('teacher_id', $request->teacher_id)->first();

  //   foreach($getPlottingTeacher as $plotting) {
  //     foreach($request->day as $day) {
  //       foreach($times as $indexTime=>$time) {
  //         $checkPlotting = Plotting::where('teacher_id', $request->teacher_id)->where('day_id', $day)->where('time_id', $time->id)->get();

  //         if($checkPlotting->count()<1) {
  //           // Update data pertama
  //           if($indexTime == 0) {
  //             $dataPlotting = [
  //               'code_room_id' => $plotting->code_room_id,
  //               'lesson_id' => $plotting->lesson_id,
  //               ' '
  //             ]
  //             Plotting::find($plotting->id)->update([''])
  //           }
  //         }
  //       }
  //     }
  //   }

  //   // foreach ($request->day_id as $day) {
  //   //   foreach($times as $time) {
  //   //     if($checkPlotting->count() <1) {
  //   //       $getPlottingByTeacherFirst = Plotting::where('teacher_id', $request->teacher_id)->first();


  //   //       if($getPlottingByTeacher)
  //   //       foreach($getPlottingByTeacher as $plottingTeacher) {

  //   //       }
  //   //     }
  //   //   }
  //   // }

  //   if ($checkPlotting->count() < 1) {
  //     if ($plotting = Plotting::create($post)) {
  //       $teacher = Teacher::find($plotting->teacher_id);
  //       return redirect()->back()->with('success', __(
  //         'plotting mengajar guru `:teacher` has been created',
  //         [
  //           'teacher' => $teacher->name,
  //         ],
  //       ));
  //     }
  //     return redirect()->back()->with('error', __(
  //       'can\'t create plotting mengajar'
  //     ));
  //   }
  //   return redirect()->back()->with('error', __(
  //     'can\'t create plotting mengajar'
  //   ));
  // }

  public function storePreferensi(Request $request)
  {
    $getPlottingTeacherAll = Plotting::where('teacher_id', $request->teacher_id)->get();
    $getPlottingTeacher = Plotting::where('teacher_id', $request->teacher_id)->get()->groupBy(['code_room_id', 'lesson_id']);
    $plotting_id = [];

    $getTimes = Time::all();
    foreach ($getPlottingTeacherAll as $plottingTeacher) {
      $plotting_id[] = $plottingTeacher->id;
    }

    foreach ($getPlottingTeacher as $data_plotting) {
      foreach ($data_plotting as $plotting) {
        foreach ($request->day_id as $day) {
          foreach ($getTimes as $time) {
            $cekResult = Result::where('code_room_id', $plotting[0]->code_room_id)->where('day_id', $day)->where('time_id', $time)->get()->count();
            if ($cekResult) {
            } else {
              Plotting::create([
                'code_room_id' => $plotting[0]->code_room_id,
                'lesson_id' => $plotting[0]->lesson_id,
                'teacher_id' => $request->teacher_id,
                'day_id' => $day,
                'time_id' => $time->id,
                'total_sks' => 2
              ]);
            }
          }
        }
      }
    }

    if (Plotting::whereIn('id', $plotting_id)->delete()) {
      $teacher = Teacher::find($request->teacher_id);
      return redirect()->back()->with('success', __(
        'plotting mengajar guru `:teacher` has been created',
        [
          'teacher' => $teacher->name,
        ],
      ));
    }
    return redirect()->back()->with('error', __(
      'can\'t create plotting mengajar'
    ));
  }
}
