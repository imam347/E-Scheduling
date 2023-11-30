<?php

namespace App\Http\Controllers;

use App\Http\Requests\DataTableRequest;
use App\Models\Day;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoremajorRequest;
use App\Http\Requests\UpdatemajorRequest;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;

class DayController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index(Day $day)
  {
    // $day = Day::all();
    return Inertia::render('Day/Index',[
      'day'=>$day,
    ]);
  }

  public function paginate(DataTableRequest $request)
    {
        $request->validated();

        return day::where(function (Builder $query) use ($request) {
                        $search = '%' . $request->input('search') . '%';
                        $columns = ['name', 'code', 'created_at', 'updated_at'];

                        foreach ($columns as $column)
                            $query->orWhere($column, 'like', $search);

                        // $query->orWhereRelation('permissions', 'name', 'like', $search);
                        // $query->orWhereRelation('roles', 'name', 'like', $search);
                    })
                    ->orderBy($request->input('order.key') ?: 'name', $request->input('order.dir') ?: 'asc')
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
            'name' => ['required', 'string'],
            'code' => ['required', 'string'],
        ]);        

        $day = Day::create([
          'name'=> $request->name,
          'code'=> $request->code,
        ]);

        if ($day) {
          return redirect()->back()->with('success', __(
              'day `:name` Berhasil ditambah', [
                  'name' => $request->name,
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
     * @param  \App\Models\Day $day
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Day $day)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'code' => ['required', 'string'],
            ]);

        if ($day->update($request->only(['name', 'code']))) {
            // $day->permissions()->sync($request->input('permissions', []));
            // $day->roles()->sync($request->input('roles', []));

            // if ($password = $request->input('password')) {
            //     $day->update([
            //         'password' => Hash::make($password),
            //     ]);
            // }

            return redirect()->back()->with('success', __(
                'day `:name` has been updated', [
                    'name' => $day->name,
                ],
            ));
        }

        return redirect()->back()->with('error', __(
            'can\'t update day'
        ));
    }
  
  /**
  * Remove the specified resource from storage.
  *
  * @param  \App\Models\day  $day
  * @return \Illuminate\Http\Response
  */
  public function destroy(day $day)
  {
    if ($day->delete()) {
      return redirect()->back()->with('success', __(
          'day `:name` has been deleted', [   
              'name' => $day->name,
          ],
      ));
  }

  return redirect()->back()->with('error', __(
      'can\'t delete day'
  ));
  }
}
