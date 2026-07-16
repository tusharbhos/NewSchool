<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MstChapter extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'release_date', 'chapter_image', 'description', 'created_by', 'status', 'slug', 'asset_path', 'class_data', 'visibility'];

    public function getClassDataAttribute($value): ?string
    {
        if ($value === null) {
            return null;
        }

        return json_encode($this->normalizeClassData($value));
    }

    public function setClassDataAttribute($value): void
    {
        $this->attributes['class_data'] = json_encode($this->normalizeClassData($value));
    }

    private function normalizeClassData($value): array
    {
        while (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return [];
            }
            $value = $decoded;
        }

        return is_array($value) ? array_values($value) : [];
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, TrnChapterTeacher::class, 'chapter_id', 'teacher_id');
    }
}