<?php

namespace App\Http\Controllers;

use App\Http\Requests\DataTableRequest;
use App\Models\Teacher;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoremajorRequest;
use App\Http\Requests\UpdatemajorRequest;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;

class TeacherController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index(Teacher $teacher)
  {
    return Inertia::render('Teacher/Index',[
      'teacher'=>$teacher,
    ]);
  }

  public function paginate(DataTableRequest $request)
    {
        $request->validated();

        return Teacher::where(function (Builder $query) use ($request) {
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

        $teacher = Teacher::create([
          'name'=> $request->name,
          'code'=> $request->code,
        ]);

        if ($teacher) {
          return redirect()->back()->with('success', __(
              'teacher `:name` Berhasil ditambah', [
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
     * @param  \App\Models\Teacher $teacher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'code' => ['required', 'string'],
            ]);

        if ($teacher->update($request->only(['name', 'code']))) {
            // $teacher->permissions()->sync($request->input('permissions', []));
            // $teacher->roles()->sync($request->input('roles', []));

            // if ($password = $request->input('password')) {
            //     $teacher->update([
            //         'password' => Hash::make($password),
            //     ]);
            // }

            return redirect()->back()->with('success', __(
                'teacher `:name` has been updated', [
                    'name' => $teacher->name,
                ],
            ));
        }

        return redirect()->back()->with('error', __(
            'can\'t update teacher'
        ));
    }
  
  /**
  * Remove the specified resource from storage.
  *
  * @param  \App\Models\teacher  $teacher
  * @return \Illuminate\Http\Response
  */
  public function destroy(teacher $teacher)
  {
    if ($teacher->delete()) {
      return redirect()->back()->with('success', __(
          'teacher `:name` has been deleted', [   
              'name' => $teacher->name,
          ],
      ));
  }

  return redirect()->back()->with('error', __(
      'can\'t delete teacher'
  ));
  }
}
