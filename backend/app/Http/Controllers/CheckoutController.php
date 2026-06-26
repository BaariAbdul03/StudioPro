<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function session(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'items' => 'required|array',
            'customer_email' => 'required|email',
        ]);

        // Mock Stripe Session Creation
        $mockSessionId = 'cs_test_' . Str::random(24);
        $mockUrl = 'https://checkout.stripe.com/pay/' . $mockSessionId;

        return response()->json([
            'id' => $mockSessionId,
            'url' => $mockUrl,
        ]);
    }
}
