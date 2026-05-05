<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Pg;

class Review extends Model
{
    /** @use HasFactory<\Database\Factories\ReviewFactory> */
    use HasFactory;
     protected $fillable = [
        'user_id',
        'pg_id',
        'rating',
        'comment'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pg()
    {
        return $this->belongsTo(Pg::class);
    }
}
