<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstClass extends Model
{
    use HasFactory;

    protected $fillable = ['class_title', 'status'];

    public function teachers()
    {
        return $this->belongsToMany(User::class, TrnTeacherClass::class, 'class_id', 'teacher_id');
    }
}
