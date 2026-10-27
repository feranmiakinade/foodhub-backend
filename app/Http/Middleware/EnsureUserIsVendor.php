<?php

namespace App\Http\Middleware;

use App\Models\VendorApplication;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsVendor
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->role !== 'vendor') {
            return response()->json([
                'message' => 'Access denied. An approved vendor account is required.',
            ], 403);
        }

        $hasApprovedApplication = VendorApplication::where('user_id', $user->id)
            ->where('status', 'approved')
            ->exists();

        if (! $hasApprovedApplication) {
            return response()->json([
                'message' => 'Your vendor application has not been approved.',
            ], 403);
        }

        return $next($request);
    }
}