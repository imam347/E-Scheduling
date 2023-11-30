<?php

namespace App\Http\Controllers;

use App\Http\Requests\DataTableRequest;
use App\Models\Time;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoremajorRequest;
use App\Http\Requests\UpdatemajorRequest;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;

class TimeController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index(Time $time)
  {
    // $time = Time::all();
    return Inertia::render('Time/Index',[
      'time'=>$time,
    ]);
  }

  public function paginate(DataTableRequest $request)
    {
        $request->validated();

        return time::where(function (Builder $query) use ($request) {
                        $search = '%' . $request->input('search') . '%';
                        $columns = [ 'code', 'range_min', 'range_max', 'sks','created_at', 'updated_at'];

                        foreach ($columns as $column)
                            $query->orWhere($column, 'like', $search);

                        // $query->orWhereRelation('permissions', 'code', 'like', $search);
                        // $query->orWhereRelation('roles', 'code', 'like', $search);
                    })
                    ->orderBy($request->input('order.key') ?: 'code', $request->input('order.dir') ?: 'asc')
                    //->with(['permissions', 'roles'])
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
            'code' => ['required', 'string'],
            'range_min' => ['required', 'time'],
            'range_max' => ['required', 'time'],
            'sks' => ['required', 'string'],
        ]);        

        $time = Time::create([
          'code'=> $request->code,
          'range_min'=> $request->range_min,
          'range_max'=> $request->range_max,
          'sks'=> $request->sks,
        ]);

        if ($time) {
          return redirect()->back()->with('success', __(
              'time `:code` Berhasil ditambah', [
                  'code' => $request->code,
              ]
          ));
      }
        return redirect()->back()->with('error', __(
            'can\'t create jurusan'
        ));
    }
  
  /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Time $time
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Time $time)
    {
        $request->validate([
            'code' => ['required', 'string'],
            'range_min' => ['required', 'time'],
            'range_max' => ['required', 'time'],
            'sks' => ['required', 'string'],
            ]);

        if ($time->update($request->only(['code','range_min', 'range_max','sks']))) {
            // $time->permissions()->sync($request->input('permissions', []));
            // $time->roles()->sync($request->input('roles', []));

            // if ($password = $request->input('password')) {
            //     $time->update([
            //         'password' => Hash::make($password),
            //     ]);
            // }

            return redirect()->back()->with('success', __(
                'time `:code` has been updated', [
                    'code' => $time->code,
                ],
            ));
        }

        return redirect()->back()->with('error', __(
            'can\'t update time'
        ));
    }
  
  /**
  * Remove the specified resource from storage.
  *
  * @param  \App\Models\Time  $time
  * @return \Illuminate\Http\Response
  */
  public function destroy(Time $time)
  {
    if ($time->delete()) {
      return redirect()->back()->with('success', __(
          'time `:code` has been deleted', [   
              'code' => $time->code,
          ],
      ));
  }

  return redirect()->back()->with('error', __(
      'can\'t delete time'
  ));
  }
}
