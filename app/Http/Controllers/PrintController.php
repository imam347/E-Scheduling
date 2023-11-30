<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Room;
use App\Models\Day;
use App\Models\Time;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PrintController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = [];
        $rooms = Room::all();

        foreach($rooms as $room) {
            $results[$room->id] = Result::where('code_room_id', $room->id)->get();
        }

        return view('print', [
            'results' => $results,
            'rooms' => $rooms,
            'days' => Day::all(),
            'times' => Time::all()
        ]);
    }
}
