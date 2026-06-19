<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;

class LeadController extends Controller
{
    // Student creates lead
    public function store(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|exists:users,id',
            'pg_id' => 'required|exists:pgs,id',
        ]);

        $lead = Lead::create([
            'student_id' => auth()->id(),
            'agent_id' => $request->agent_id,
            'pg_id' => $request->pg_id,
            'status' => 'new',
        ]);

        return response()->json([
            'message' => 'Lead created successfully',
            'data' => $lead
        ]);
    }

    // Agent views own leads
    public function myLeads()
    {
        dd(111);
        $leads = Lead::with([
            'student:id,name,email,mobile',
            'pg:id,name'
        ])
        ->where('agent_id', auth()->id())
        ->latest()
        ->get();

        return response()->json($leads);
    }

    // Update status
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:new,contacted,visit_scheduled,joined,rejected'
        ]);

        $lead = Lead::findOrFail($id);

        if ($lead->agent_id != auth()->id()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $lead->update([
            'status' => $request->status
        ]);

        return response()->json([
            'message' => 'Status updated successfully',
            'data' => $lead
        ]);
    }
}
