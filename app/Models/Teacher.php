<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $table = 'teachers';
    protected $fillable = ['name', 'email', 'phone'];

    //Teacher k Subjects
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subject');
    }

    public function classes()
    {
        return $this->belongsToMany(SchoolClass::class, 'class_teacher', 'class_id');
    }
}
