<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrnTeacherClass extends Model
{
    use HasFactory;

    protected $fillable = ['teacher_id', 'class_id', 'status'];
}
