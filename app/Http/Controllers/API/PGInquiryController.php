<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\PGInquiry;
use App\Http\Requests\StorePGInquiryRequest;
use App\Http\Requests\UpdatePGInquiryRequest;
use Illuminate\Http\Request;
use App\Models\PG;

class PGInquiryController extends Controller
{
    public function __construct() {
       
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        $pg = PG::findOrFail($request->pg_id);

        $inquiry = PGInquiry::create([
            'pg_id' => $pg->id,
            'student_name' => $request->student_name,

            'student_phone' => $request->student_phone,

            'student_email' => $request->student_email,

            'message' => $request->message,
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(PGInquiry $pGInquiry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PGInquiry $pGInquiry)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePGInquiryRequest $request, PGInquiry $pGInquiry)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PGInquiry $pGInquiry)
    {
        //
    }
    
    public function pgLeads($pgId){
        $leads = PGInquiry::with('pg')->where('pg_id', $pgId)->latest()->paginate(3);
        return response()->json($leads);
    }
}
