<?php

namespace App\Http\Controllers;

use App\Http\Requests\DataTableRequest;
use App\Models\Lesson;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoremajorRequest;
use App\Http\Requests\UpdatemajorRequest;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;

class LessonController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index(Lesson $lesson)
  {
    // $lesson = Lesson::all();
    return Inertia::render('Lesson/Index',[
      'lesson'=>$lesson,
    ]);
  }

  public function paginate(DataTableRequest $request)
    {
        $request->validated();

        return lesson::where(function (Builder $query) use ($request) {
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
            'sks' => ['required', 'string'],
        ]);        

        $lesson = Lesson::create([
          'name'=> $request->name,
          'code'=> $request->code,
          'sks'=> $request->sks,
        ]);

        if ($lesson) {
          return redirect()->back()->with('success', __(
              'lesson `:name` Berhasil ditambah', [
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
     * @param  \App\Models\Lesson $lesson
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lesson $lesson)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'code' => ['required', 'string'],
            'sks' => ['required', 'string'],
            ]);

        if ($lesson->update($request->only(['name', 'code']))) {
            // $lesson->permissions()->sync($request->input('permissions', []));
            // $lesson->roles()->sync($request->input('roles', []));

            // if ($password = $request->input('password')) {
            //     $lesson->update([
            //         'password' => Hash::make($password),
            //     ]);
            // }

            return redirect()->back()->with('success', __(
                'lesson `:name` has been updated', [
                    'name' => $lesson->name,
                ],
            ));
        }

        return redirect()->back()->with('error', __(
            'can\'t update lesson'
        ));
    }
  
  /**
  * Remove the specified resource from storage.
  *
  * @param  \App\Models\lesson  $lesson
  * @return \Illuminate\Http\Response
  */
  public function destroy(lesson $lesson)
  {
    if ($lesson->delete()) {
      return redirect()->back()->with('success', __(
          'lesson `:name` has been deleted', [   
              'name' => $lesson->name,
          ],
      ));
  }

  return redirect()->back()->with('error', __(
      'can\'t delete lesson'
  ));
  }
}
