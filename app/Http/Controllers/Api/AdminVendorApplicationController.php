<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VendorApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminVendorApplicationController extends Controller
{
    public function index(): JsonResponse
    {
        $applications = VendorApplication::with('user:id,name,email')
            ->latest()
            ->paginate(15);

        return response()->json([
            'applications' => $applications,
        ]);
    }

    public function approve(Request $request, VendorApplication $vendorApplication): JsonResponse
    {
        if ($vendorApplication->status !== 'pending') {
            return response()->json([
                'message' => 'This application has already been reviewed.',
            ], 409);
        }

        $validated = $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $vendorApplication->update([
    'status' => 'approved',
    'admin_notes' => $validated['admin_notes'] ?? null,
    'reviewed_at' => now(),
]);

$vendorApplication->user()->update([
    'role' => 'vendor',
]);

        return response()->json([
            'message' => 'Vendor application approved successfully.',
            'application' => $vendorApplication->fresh()->load('user:id,name,email'),
        ]);
    }

    public function reject(Request $request, VendorApplication $vendorApplication): JsonResponse
    {
        if ($vendorApplication->status !== 'pending') {
            return response()->json([
                'message' => 'This application has already been reviewed.',
            ], 409);
        }

        $validated = $request->validate([
            'admin_notes' => ['required', 'string', 'max:2000'],
        ]);

        $vendorApplication->update([
            'status' => 'rejected',
            'admin_notes' => $validated['admin_notes'],
            'reviewed_at' => now(),
        ]);

        return response()->json([
            'message' => 'Vendor application rejected.',
            'application' => $vendorApplication->fresh()->load('user:id,name,email'),
        ]);
    }
}