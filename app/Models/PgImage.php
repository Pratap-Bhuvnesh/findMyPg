<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PgImage extends Model
{
    protected $table = 'pg_images';

    protected $fillable = [
        'pg_id',
        'image_path',
        'image_type',
        'display_order',
    ];
     public function pgs()
    {
        return $this->hasMany(PG::class, 'pg_id');
    }
}
