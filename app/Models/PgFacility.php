<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PgFacility extends Model
{
    /** @use HasFactory<\Database\Factories\PgFacilityFactory> */
    use HasFactory;
    protected $fillable = [
        'pg_id',
        'amenities',
        'available'
    ];
}
