<?php

namespace App\Http\Controllers;

use App\Http\Requests\DataTableRequest;
use App\Models\Room;
use App\Http\Controllers\Controller;
use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;

class RoomController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Room $room)
  {
    return Inertia::render('Room/Index', [
      'room' => $room,
      'majors' => Major::get(['id', 'name']),
    ]);
  }

  public function paginate(DataTableRequest $request)
  {
    $request->validated();
    //   $user = $request->user();

    return Room::where(function (Builder $query) use ($request) {
      $search = '%' . $request->search . '%';
      $model = $query->getModel();

      foreach ($model->getFillable() as $column) {
        $query->orWhere($column, ' like', $search);
      }
      $query->orWhereRelation('major', 'name', 'like', $search);
    })
      ->orderBy($request->input('order.key') ?: 'created_at', $request->input('order.by') ?: 'desc')
      //->when(!$user->hasRole(['superuser', 'risk assessment']), fn (Builder $query) => $query->where('created_by_id', $user->id)->orWhereRelation('users', 'users.id', $user->id))
      ->select([
        'id',
        'name',
        'code',
        'major_id',
        'created_at',
        'updated_at',

      ])
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
    $request->validate([
      'name' => 'required|string',
      'code' => 'required|string',
      // 'major' => 'nullable|integer:majors',
    ]);
    //     $major = Major::find($request->major_id);

    // if (!$major) {
    //     return redirect()->back()->with('error', __('Jurusan tidak ditemukan'));
    // }     

    $room = Room::create([
      'name' => $request->name,
      'code' => $request->code,
      'major_id' => $request->major()->id,
    ]);
    // $room->major()->associate($major);

    if ($room) {
      return redirect()->back()->with('success', __(
        'room `:name` Berhasil ditambah',
        [
          'name' => $request->name,
        ]
      ));
    }
    return redirect()->back()->with('error', __(
      'can\'t create ruangan'
    ));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Room $room
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Room $room)
  {
    $request->validate([
      'name' => 'required|string',
      'code' => 'required|string',
      'major_id' => 'nullable|integer:majors',
    ]);

    if ($room->update($request->only(['name', 'code']))) {
      $room->majors()->sync($request->input('majors', []));

      return redirect()->back()->with('success', __(
        'room `:name` has been updated',
        [
          'name' => $room->name,
        ],
      ));
    }

    return redirect()->back()->with('error', __(
      'can\'t update room'
    ));
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\room  $room
   * @return \Illuminate\Http\Response
   */
  public function destroy(room $room)
  {
    if ($room->delete()) {
      return redirect()->back()->with('success', __(
        'room `:name` has been deleted',
        [
          'name' => $room->name,
        ],
      ));
    }

    return redirect()->back()->with('error', __(
      'can\'t delete room'
    ));
  }

  //   public function detachMajors(Room $room, Major $major)
  //   {
  //       if ($room->permissions()->detach([$major->id])) {
  //           return redirect()->back()->with('success', __(
  //               'major `:major from room `:room` has been detached`', [
  //                   'major' => $major->name,
  //                   'room' => $room->name,
  //               ]
  //           ));
  //       }

  //       return redirect()->back()->with('error', __(
  //           'can\'t detach major `:major` from room `:room`', [
  //               'major' => $major->name,
  //               'room' => $room->name,
  //           ]
  //       ));
  //   }
}
