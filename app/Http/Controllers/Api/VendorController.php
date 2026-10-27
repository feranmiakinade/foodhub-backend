<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VendorApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function dashboard(Request $request): JsonResponse
    {
        $application = VendorApplication::where('user_id', $request->user()->id)
            ->where('status', 'approved')
            ->firstOrFail();

        return response()->json([
            'message' => 'Welcome to your FoodHub vendor dashboard.',
            'vendor' => [
                'user_id' => $request->user()->id,
                'account_name' => $request->user()->name,
                'business_name' => $application->business_name,
                'business_type' => $application->business_type,
                'contact_name' => $application->contact_name,
                'contact_phone' => $application->contact_phone,
                'contact_email' => $application->contact_email,
                'business_address' => $application->business_address,
                'city' => $application->city,
                'application_status' => $application->status,
            ],
        ]);
    }
}