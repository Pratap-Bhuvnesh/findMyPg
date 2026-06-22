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
            'student_name' => $request->name,

            'student_phone' => $request->phone,

            'student_email' => $request->email,

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
        $leads = PGInquiry::with('pg')->where('pg_id', $pgId)->latest()->paginate(20);
        $statusCounts = PGInquiry::where('pg_id', $pgId)->select('status', \DB::raw('COUNT(*) as total'))->groupBy('status')->pluck('total', 'status');;
        return response()->json([
            'leads' => $leads,
            'status_counts' => $statusCounts,
        ]);
    }

    public function updateStatus(Request $request, $id){
        $request->validate([
            'status' => 'required|in:new,contacted,visited,joined,closed'
        ]);

        $lead = PGInquiry::findOrFail($id);

        $lead->update([
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lead status updated successfully.',
            'lead' => $lead
        ]);
    }
}
