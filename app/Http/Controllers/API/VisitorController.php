<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VisitorCount;

class VisitorController extends Controller
{
    public function track()
    {
        $ip = request()->ip();
        // always get first row or create if not exists
        $visitor = VisitorCount::firstOrCreate([
            'id' => 1
        ], [
            'count' => 0, 'ip_address' => $ip
        ]);

        $visitor->increment('count');

        return response()->json([
            'count' => $visitor->count
        ]);
    }

    public function count()
    {
        $visitor = VisitorCount::first();

        return response()->json([
            'count' => $visitor->count ?? 0
        ]);
    }
}
