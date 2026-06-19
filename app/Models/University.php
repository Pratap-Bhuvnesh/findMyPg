<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PG;

class University extends Model
{
    /** @use HasFactory<\Database\Factories\UniversityFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'lat',
        'lng',
    ]; 
    public function pgs(){
        return $this->hasMany(PG::class);
    }  
}
