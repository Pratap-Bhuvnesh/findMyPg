<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PG;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PGController extends Controller
{
    
    public function index(Request $request){ 
        $query = PG::query();
        $query->where('active', '=', '1');
        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->food_available) {
            $query->where('food_available', true);
        }
        $query->orderBy('created_at', 'desc');
        $pgs = $query->with(['owner','reviews.user', 'amenities:pg_id,amenities,available'])->withCount('reviews')->withAvg('reviews', 'rating')->paginate(45);        
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
        //dd($request->all());
        if (auth()->user()->role !== 'owner') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $request->food_available = 1;
        if ($request->food == 'Not Available') {
            $request->food_available = 0;
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
        if (!empty($request->amenities)) {
            foreach ($request->amenities as $amenity) {
                $pg->amenities()->create([
                    'amenities' => $amenity,
                    'available' => 1
                ]);
            }
        }

        return response()->json($pg);
    }


    public function show($id)
    {
        $pg = PG::with(['amenities', 'images'])
            ->where('owner_id', auth()->id())
            ->findOrFail($id);

        return response()->json($pg);
    }
    public function update(Request $request, $id)
    {
        $pg = PG::where('owner_id', auth()->id())
            ->findOrFail($id);

        $pg->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'location' => $request->location,
            'gender' => $request->gender,
            'sharing' => $request->sharing,
            'food_available' => $request->food === 'Not Available' ? 0 : 1,
        ]);

        // Delete old amenities
        $pg->amenities()->delete();

        // Insert new amenities
        if (!empty($request->amenities)) {
            foreach ($request->amenities as $amenity) {
                $pg->amenities()->create([
                    'amenities' => $amenity,
                    'available' => 1
                ]);
            }
        }

        return response()->json([
            'message' => 'PG updated successfully',
            'data' => $pg->load('amenities')
        ]);
    }
    public function destroy($id)
    {
        $pg = PG::where('owner_id', auth()->id())
            ->findOrFail($id);

        // Delete amenities
        $pg->amenities()->delete();

       \DB::table('pg_images')
        ->where('pg_id', $pg->id)
        ->delete();

        // Delete PG
        $pg->delete();

        return response()->json([
            'message' => 'PG deleted successfully'
        ]);
    }

     public function mypglist(Request $request){
        $query = PG::query();
        $query->where('active', '=', '1');
        
        $query->where('owner_id', auth()->id());
        $query->orderBy('created_at', 'desc');
        $pgs = $query->with(['owner','reviews.user', 'amenities:pg_id,amenities,available'])->withCount('reviews')->withAvg('reviews', 'rating')->paginate(45);        
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
}
