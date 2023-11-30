<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Result extends Model
{
  use HasFactory;
  
  protected $fillable =[
    'code_room_id',
    'code_major_id',
    'lesson_id',
    'teacher_id',
    'day_id',
    'time_id',
];

public function Room() {
  return $this->belongsTo(Room::class, 'code_room_id', 'id');
}

// public function Major() {
//   return $this->belongsTo(Major::class, 'code_major_id', 'id');
// }

public function Lesson() {
  return $this->belongsTo(Lesson::class, 'lesson_id', 'id');
}

public function Teacher() {
  return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
}

public function Day() {
  return $this->belongsTo(Major::class, 'day_id', 'id');
}

public function Time() {
  return $this->belongsTo(Time::class, 'time_id', 'id');
}
}




