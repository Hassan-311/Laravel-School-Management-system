<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class fee extends Model
{
    protected $fillable = ['student_id', 'amount', 'month', 'year', 'status'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
