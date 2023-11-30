<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Time extends Model
{
  use HasFactory;

  protected $fillable = [
    'range_min',
    'range_max',
    'sks',
  ];
}




