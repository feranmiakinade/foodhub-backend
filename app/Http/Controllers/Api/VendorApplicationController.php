<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VendorApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VendorApplicationController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        // Prevent duplicate pending applications or reapplying after approval.
        $existingApplication = VendorApplication::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($existingApplication) {
            return response()->json([
                'message' => 'You already have a pending or approved vendor application.',
            ], 409);
        }

        $validated = $request->validate([
            'business_name' => ['required', 'string', 'max:255'],
            'business_type' => ['nullable', 'string', 'max:100'],
            'contact_name' => ['required', 'string', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:20'],
            'contact_email' => ['required', 'email', 'max:255'],
            'business_address' => ['required', 'string', 'max:2000'],
            'city' => ['required', 'string', 'max:100'],
            'business_description' => ['nullable', 'string', 'max:3000'],
        ]);

        $application = VendorApplication::create([
            ...$validated,
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Vendor application submitted successfully. It is awaiting review.',
            'application' => $application,
        ], 201);
    }
}