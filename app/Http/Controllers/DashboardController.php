<?php

namespace App\Http\Controllers;

use App\Models\Day;
use App\Models\Lesson;
use App\Models\Major;
use App\Models\Room;
use App\Models\Teacher;
use App\Models\Time;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Dashboard',[
        'majors' => Major::all(),
        'teachers' => Teacher::all(),
        'lessons' => Lesson::all(),
        'rooms' => Room::all(),
        'days' => Day::all(),
        'times' => Time::all(),
        ]);
    }
}
