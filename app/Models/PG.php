<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\PgFacility;
use App\Models\PgImage;

class PG extends Model{
    use HasFactory;
    protected $table = 'pgs';
    protected $fillable = [
        'owner_id',
        'name',
        'description',
        'price',
        'location',
        'food_available',
    ];
    public function owner(){
        return $this->belongsTo(User::class, 'owner_id');
    }
    public function facilities(){
        return $this->hasMany(PgFacility::class, 'pg_id');
    }   
}
