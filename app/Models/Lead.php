<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lead extends Model
{
     use HasFactory;
     protected $fillable = [
        'student_id',
        'agent_id',
        'pg_id',
        'status'
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function pg()
    {
        return $this->belongsTo(Pg::class, 'pg_id');
    }
}
