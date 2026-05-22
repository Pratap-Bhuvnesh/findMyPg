<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PG;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PGController extends Controller
{
    
    public function index(Request $request){
        
        $query = PG::query();

        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->food_available) {
            $query->where('food_available', true);
        }
        $pgs = $query->with(['owner', 'facilities'])->get();
        if ($pgs->isNotEmpty()) {
           
            // 3. Collect all parent IDs to fetch images in bulk
            $pgIds = $pgs->pluck('id')->toArray();

            // 4. Run exactly ONE bulk database query for all relevant images
            $allImages = DB::table('pg_images')
                ->whereIn('pg_id', $pgIds)
                ->orderBy('display_order', 'asc')
                ->get()
                ->groupBy('pg_id'); // Groups items by their pg_id automatically
             // 5. Map the pre-grouped images onto each PG model row
            $pgs->each(function ($pg) use ($allImages) {
                // Assign the grouped collection, or an empty collection if no images exist
                $pg->images = $allImages->get($pg->id, collect([]));
            });
        }
        return response()->json($pgs);
    }

    public function store(Request $request){
        //dd(auth()->user()->role);
        if (auth()->user()->role !== 'owner') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $pg = PG::create([
            'owner_id' => auth()->id(),
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'location' => $request->location,
            'food_available' => $request->food_available
        ]);
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('pgs', 'public');

                $pg->images()->create([
                    'image_path' => $path
                ]);
            }
        }
        $pg->facilities()->create([
            'wifi' => $request->wifi,
            'ac' => $request->ac,
            'laundry' => $request->laundry,
            'parking' => $request->parking,
        ]);

        return response()->json($pg);
    }
}
