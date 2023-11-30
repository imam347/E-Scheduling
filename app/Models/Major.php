<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
  use HasFactory;
  protected $fillable = [
    'name',
    'code',
  ];

  public function room()
  {
      return $this->hasMany(Room::class, 'major_id');
  }
  
}
