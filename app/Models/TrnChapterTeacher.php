<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrnChapterTeacher extends Model
{
    use HasFactory;

    protected $fillable = ['chapter_id', 'teacher_id', 'status', 'seen_status', 'seen_date'];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function chapter()
    {
        return $this->belongsTo(MstChapter::class, 'chapter_id');
    }
}
