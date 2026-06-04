<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PG;
class PGInquiry extends Model
{
    /** @use HasFactory<\Database\Factories\PGInquiryFactory> */
    use HasFactory;
    protected $fillable = [
        'pg_id',       
        'student_name',
        'student_phone',
        'student_email',
        'message',
        'status',
    ];
  
    protected $table = 'pg_inquiries';
    public function pg() {
        return $this->belongsTo(PG ::class);
    } 
 
}
