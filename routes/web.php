<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DayController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TimeController;
use App\Http\Controllers\MajorController;
use App\Http\Controllers\PrintController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\PlottingController;
use App\Http\Controllers\AlgoritmaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TranslationController;
use App\Http\Controllers\Superuser\MenuController;
use App\Http\Controllers\Superuser\RoleController;
use App\Http\Controllers\Superuser\UserController;
use App\Http\Controllers\GeneratePlottingController;
use App\Http\Controllers\Superuser\PermissionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    // Route::get('/', function () {
    //     return Inertia::render('Dashboard');
    // })->name('dashboard');

    Route::get('/', [DashboardController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::resource('major', App\Http\Controllers\MajorController::class)->only(['index','store','update','destroy' ]);
    Route::post('/major/paginate', [App\Http\Controllers\MajorController::class, 'paginate'])->name('major.paginate');
    
    Route::resource('time', App\Http\Controllers\TimeController::class)->only(['index','store','update','destroy' ]);
    Route::post('/time/paginate', [App\Http\Controllers\TimeController::class, 'paginate'])->name('time.paginate');

    Route::resource('teacher', App\Http\Controllers\TeacherController::class)->only(['index','store','update','destroy' ]);
    Route::post('/teacher/paginate', [App\Http\Controllers\TeacherController::class, 'paginate'])->name('teacher.paginate');
    
    Route::resource('day', App\Http\Controllers\DayController::class)->only(['index','store','update','destroy' ]);
    Route::post('/day/paginate', [App\Http\Controllers\DayController::class, 'paginate'])->name('day.paginate');
    
    Route::resource('lesson', App\Http\Controllers\LessonController::class)->only(['index','store','update','destroy' ]);
    Route::post('/lesson/paginate', [App\Http\Controllers\LessonController::class, 'paginate'])->name('lesson.paginate');
    
    Route::resource('room', App\Http\Controllers\RoomController::class)->only(['index','store','update','destroy' ]);
    Route::post('/room/paginate', [App\Http\Controllers\RoomController::class, 'paginate'])->name('room.paginate');

    Route::post('/plotting/generate-schedule',[App\Http\Controllers\GeneratePlottingController::class, 'save'])->name('generate_schedule.save');

    Route::get('/plotting/generate-schedule/generate/{id}', [AlgoritmaController::class, 'generateSchedule'])->name('generate_schedule');
    
    Route::post('/plotting/preferensi', [App\Http\Controllers\PlottingController::class, 'storepreferensi'])->name('plotting.store_preferensi');
    Route::resource('plotting', App\Http\Controllers\PlottingController::class)->only(['index','store','update','destroy' ]);
    Route::post('/plotting/paginate', [App\Http\Controllers\PlottingController::class, 'paginate'])->name('plotting.paginate');

    Route::get('/print_schedule', [App\Http\Controllers\PrintController::class, 'index'])->name('print_schedule');

    Route::prefix('/superuser')->name('superuser.')->group(function () {
        Route::resource('permission', App\Http\Controllers\Superuser\PermissionController::class)->only([
            'index', 'store', 'update', 'destroy',
        ])->middleware(['permission:read permission']);

        Route::resource('role', App\Http\Controllers\Superuser\RoleController::class)->only([
            'index', 'store', 'update', 'destroy',
        ])->middleware(['permission:read role']);

        Route::patch('/role/{role}/detach/{permission}', [App\Http\Controllers\Superuser\RoleController::class, 'detach'])->name('role.detach')->middleware(['permission:update role']);

        Route::resource('user', App\Http\Controllers\Superuser\UserController::class)->only([
            'index', 'store', 'update', 'destroy',
        ])->middleware(['permission:read user']);

        Route::prefix('/user/{user}')->name('user.')->controller(App\Http\Controllers\Superuser\UserController::class)->middleware(['permission:update user'])->group(function () {
            Route::patch('/role/{role}/detach', 'detachRole')->name('role.detach');
            Route::patch('/permission/{permission}/detach', 'detachPermission')->name('permission.detach');
        });

        Route::patch('/menu/save', [App\Http\Controllers\Superuser\MenuController::class, 'save'])->name('menu.save')->middleware(['permission:update menu']);
        Route::resource('menu', App\Http\Controllers\Superuser\MenuController::class)->only([
            'index', 'store', 'update', 'destroy',
        ])->middleware(['permission:read menu']);
        Route::get('/menu/{menu}/counter', [App\Http\Controllers\Superuser\MenuController::class, 'counter'])->name('menu.counter');

        Route::prefix('/translation')->name('translation.')->controller(App\Http\Controllers\TranslationController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::patch('/', 'update')->name('update');
        });
        
        Route::get('/activity/login', [App\Http\Controllers\ActivityController::class, 'login'])->name('activity.login');

        Route::get('/user/{user}/menu', fn (App\Models\User $user) => $user->menus())->name('user.menu');
        Route::get('/permission/get', [App\Http\Controllers\Superuser\PermissionController::class, 'get'])->name('permission');
        Route::get('/role/get', [App\Http\Controllers\Superuser\RoleController::class, 'get'])->name('role');
        Route::post('/role/paginate', [App\Http\Controllers\Superuser\RoleController::class, 'paginate'])->name('role.paginate');
        Route::post('/user/paginate', [App\Http\Controllers\Superuser\UserController::class, 'paginate'])->name('user.paginate');
        Route::post('/activity/login', [App\Http\Controllers\ActivityController::class, 'logins'])->name('activity.login');
        Route::get('/menu/get', [App\Http\Controllers\Superuser\MenuController::class, 'get'])->name('menu');
    });
});