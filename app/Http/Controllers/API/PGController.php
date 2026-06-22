<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PG;
use App\Models\Review;
use App\Models\PgImage;
use App\Models\PgFacility;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

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

        if ($request->has('food_available')) {
            $query->where('food_available', $request->boolean('food_available'));
        }
        if ($request->search) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('pgs.name', 'like', "%{$search}%")
                ->orWhere('pgs.location', 'like', "%{$search}%")
                ->orWhereHas('university', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%");
                });
            });
        }

        $query->orderBy('updated_at', 'desc');
       
        $pgs = $query->with(['owner:id,name,email,mobile,role',
        'images:id,pg_id,image_path,image_type,display_order','university',
        'reviews.user', 'amenities:pg_id,amenities,available'
        ])->withCount('reviews')->withAvg('reviews', 'rating')->paginate(45);        
       
        /* if ($pgs->isNotEmpty()) {
           
            // 3. Collect all parent IDs to fetch images in bulk
            $pgIds = $pgs->pluck('id')->toArray();

            // 4. Run exactly ONE bulk database query for all relevant images
            $allImages = DB::table('pg_images')
                ->select(id,image_path, image_type, display_order')
                ->whereIn('pg_id', $pgIds)
                ->orderBy('display_order', 'asc')
                ->get()
                ->groupBy('pg_id'); // Groups items by their pg_id automatically
             // 5. Map the pre-grouped images onto each PG model row
            $pgs->each(function ($pg) use ($allImages) {
                // Assign the grouped collection, or an empty collection if no images exist
                $pg->images = $allImages->get($pg->id, collect([]));
            });
        }   */      
        return response()->json($pgs);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',            
            'location' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'amenities' => 'required',            
            'latitude' => 'required|string|max:255',
            'longitude' => 'required|string|max:255',      
            'rent_type' => 'required|string',         
            'university_id' => 'required|integer|exists:universities,id',
            'sharingPrices' => ['required',
                                'array',
                                function ($attribute, $value, $fail) {
                                    $hasPrice = collect($value)
                                        ->filter(fn ($price) => !empty($price))
                                        ->isNotEmpty();

                                    if (!$hasPrice) {
                                        $fail('At least one sharing price is required.');
                                    }
                                },
                            ],
            // Images
            'images' => $request->id ? 'nullable|array' : 'required|array|min:2',
            'images.*' => $request->hasFile('images') ? 'image|mimes:jpg,jpeg,png,webp|max:5120' : 'nullable',
            ],            
            [
                'food_available.required' => 'Please select available food type.',
                'images.required' => 'Please upload at least two image.',
                'latitude.required' => 'Please allow and turn on location.',
                'longitude.required' => 'Please allow and turn on location.',
                'university_id.required' => 'Please select nearest university.',                
                'rent_type.required' => 'Please select rent basis.',                
                'amenities.required' => 'Please select atleast one amenity.',                
                'images.min' => 'Please upload at least two image.',
                'images.*.image' => 'Each file must be a valid image.',
                'images.*.mimes' => 'Images must be JPG, JPEG, PNG, or WEBP.',
                'images.*.max' => 'Each image must not exceed 5 MB.',
            ]
        );
        if ($validator->fails()) {           
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        
        if (auth()->user()->role !== 'owner') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $university = University::findOrFail($request->university_id);                
        
        $distanceBetween    =  calculateDistance($request->latitude, $request->longitude, $university->lat, $university->lng, 'km');        
       
        $recievedInput = [
            'owner_id' => auth()->id(),                
            'food_available' => !empty($request->food) || in_array('Food', $request->amenities ?? []) ? 1 : 0,
            'food' => $request->food,
            'name' => $request->name,
            'description' => $request->description,
            'price' => collect($request->sharingPrices)->filter()->min(),
            'location' => $request->location,
            'gender' => $request->gender,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'distance' => $distanceBetween,
            'university_id' => $request->university_id,
            'rent_type' => $request->rent_type,
            'accomodation_sharing_prices' => $request->sharingPrices ?? [],
        ];
        if ($request->filled('accomodation_type')) {
            $recievedInput['accomodation_type'] = $request->accomodation_type;
        }

        if ($request->id) {
           
            $remainingImages =PgImage::where('pg_id', $request->id)->count() - count($request->deleted_images ?? []);

            $newImages = count($request->file('images', []));

            if (($remainingImages + $newImages) < 1) {
                return response()->json([
                    'errors' => [
                        'images' => ['Please upload at least one image.']
                    ]
                ], 422);
            }
        }//dd($recievedInput);
        $pg = PG::updateOrCreate(
            ['id' => $request->id], // condition to find existing record
            $recievedInput
        );   
        if ($request->filled('deleted_images')) {

            foreach ($request->deleted_images as $imagePath) {

                $image = PgImage::where('pg_id', $pg->id)
                    ->where('image_path', $imagePath)
                    ->first();

                if ($image) {

                    Storage::disk('public')->delete($image->image_path);

                    $image->delete();
                }
            }
        }     
       if ($request->hasFile('images')) {

            //$order = PgImage::where('pg_id', $pg->id)->count();
            $order = PgImage::where('pg_id', $pg->id)->max('display_order') ?? 0;
            foreach ($request->file('images') as $image) {

                $path = $image->store('pgs', 'public');

                $order++;

                PgImage::create([
                    'pg_id' => $pg->id,
                    'image_path' => $path,
                    'image_type' => $order == 1 ? 'room' : 'building',
                    'display_order' => $order,
                ]);
            }
        }
        if (!empty($request->amenities)) {
            PgFacility::where('pg_id', $pg->id)->delete();
            $amenities = collect($request->amenities ?? [])
                ->filter()
                ->map(fn($a) => trim($a))
                ->unique(fn($a) => strtolower($a))
                ->values()
                ->toArray();
            foreach ($amenities as $amenity) {
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
        /* $pg = PG::where('owner_id', auth()->id())->findOrFail($id);

        $images = PgImage::where('pg_id', $id)->pluck('image_path');
        Storage::disk('public')->delete($images); 

        // Delete amenities
        $pg->amenities()->delete();

       PgImage::where('pg_id', $pg->id)->delete();

        // Delete PG
        $pg->delete();
 */
        $pg = Pg::with(['amenities', 'reviews', 'images'])->findOrFail($id);
        $paths = $pg->images()->pluck('image_path')->toArray();
        Storage::disk('public')->delete($paths);

        // Delete related records
        $pg->amenities()->delete();
        $pg->reviews()->delete();
        $pg->images()->delete();

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
        $query->orderBy('pgs.id', 'desc');        
        $pgs = $query->with(['owner:id,name,email,mobile,role','university','reviews.user', 'amenities:pg_id,amenities,available'])->withCount('reviews')->withAvg('reviews', 'rating')->paginate(45);        
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
