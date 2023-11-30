<?php

namespace App\Http\Controllers;

use App\Http\Requests\DataTableRequest;
use App\Models\Major;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoremajorRequest;
use App\Http\Requests\UpdatemajorRequest;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;

class MajorController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index(Major $major)
  {
    // $major = Major::all();
    return Inertia::render('Major/Index',[
      'major'=>$major,
    ]);
  }

  public function paginate(DataTableRequest $request)
    {
        $request->validated();

        return major::where(function (Builder $query) use ($request) {
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

        $major = Major::create([
          'name'=> $request->name,
          'code'=> $request->code,
        ]);

        if ($major) {
          return redirect()->back()->with('success', __(
              'major `:name` Berhasil ditambah', [
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
     * @param  \App\Models\Major $major
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Major $major)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'code' => ['required', 'string'],
            ]);

        if ($major->update($request->only(['name', 'code']))) {
            // $major->permissions()->sync($request->input('permissions', []));
            // $major->roles()->sync($request->input('roles', []));

            // if ($password = $request->input('password')) {
            //     $major->update([
            //         'password' => Hash::make($password),
            //     ]);
            // }

            return redirect()->back()->with('success', __(
                'major `:name` has been updated', [
                    'name' => $major->name,
                ],
            ));
        }

        return redirect()->back()->with('error', __(
            'can\'t update major'
        ));
    }
  
  /**
  * Remove the specified resource from storage.
  *
  * @param  \App\Models\major  $major
  * @return \Illuminate\Http\Response
  */
  public function destroy(major $major)
  {
    if ($major->delete()) {
      return redirect()->back()->with('success', __(
          'major `:name` has been deleted', [   
              'name' => $major->name,
          ],
      ));
  }

  return redirect()->back()->with('error', __(
      'can\'t delete major'
  ));
  }
}
