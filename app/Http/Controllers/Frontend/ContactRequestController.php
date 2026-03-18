<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use App\Models\ContactRequest;
use Illuminate\Http\Request;

class ContactRequestController extends FrontendController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * AJAX: Store visit request
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'preferred_date' => 'nullable|date',
            'preferred_time' => 'nullable|string',
            'message' => 'nullable|string',
        ]);

        $validated['status'] = 'pending';

        ContactRequest::create($validated);

        return response()->json(['success' => true, 'message' => 'Yêu cầu đã được ghi nhận.']);
    }
}
