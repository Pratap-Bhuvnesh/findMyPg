<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\PgFacility;
use App\Models\PgImage;
use App\Models\Review;
use App\Models\PGInquiry;
use App\Models\University;

class PG extends Model{
    use HasFactory;
    protected $table = 'pgs';
    protected $fillable = [
        'id',
        'owner_id',
        'name',
        'description',
        'price',
        'gender',
        'location',
        'food_available',
        'food',
        'latitude',
        'longitude',
        'university_id',
        'distance',
        'accomodation_sharing_prices',
        'accomodation_type',
        'rent_type',
    ];
    protected $casts = [
        'accomodation_sharing_prices' => 'array',
    ];
    public function owner(){
        return $this->belongsTo(User::class, 'owner_id');
    }
    public function amenities(){
        return $this->hasMany(PgFacility::class, 'pg_id');
    }   
    // ADD THIS
    public function reviews()
    {
        return $this->hasMany(Review::class, 'pg_id');
    }
    protected $appends = ['verified'];

    public function getVerifiedAttribute()
    {
        return (bool) $this->is_verified;
    }
    
    public function inquiries()
    {
        return $this->hasMany(PGInquiry::class);
    } 
    public function images()
    {
        return $this->hasMany(PgImage::class, 'pg_id');
    }
    public function university()
    {
        return $this->belongsTo(University::class);
    }
    protected static function booted(){
        static::saving(function ($pg) {
            if (is_null($pg->food)) {
                $pg->food_available = 0;
            }
        });
    }
}
