<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function session(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.variant_id' => 'nullable|exists:product_variants,id',
            'customer_email' => 'required|email',
        ]);

        // Verify the authenticated user owns this project
        $project = Project::findOrFail($validated['project_id']);
        abort_unless($project->user_id === auth()->id(), 403, 'You do not own this project.');

        $mockSessionId = 'cs_test_' . Str::random(24);
        
        $totalAmount = 0;
        foreach ($validated['items'] as $item) {
            $product = Product::findOrFail($item['product_id']);
            abort_unless((int) $product->project_id === (int) $validated['project_id'], 422, 'Product does not belong to this project.');
            abort_if($product->inventory_status === 'out_of_stock' || $product->inventory_quantity < $item['quantity'], 422, 'Product is out of stock.');
            $price = $product->price;
            if (!empty($item['variant_id'])) {
                $variant = ProductVariant::findOrFail($item['variant_id']);
                abort_unless((int) $variant->product_id === (int) $product->id, 422, 'Variant does not belong to this product.');
                abort_if($variant->inventory_quantity < $item['quantity'], 422, 'Variant is out of stock.');
                $price = $variant->price;
            }
            $totalAmount += $price * $item['quantity'];
        }

        $order = Order::create([
            'project_id' => $validated['project_id'],
            'stripe_session_id' => $mockSessionId,
            'customer_email' => $validated['customer_email'],
            'total_amount' => $totalAmount,
            'payment_status' => 'pending',
            'shipping_details' => [
                'name' => 'Jane Doe',
                'address' => [
                    'line1' => '456 Market St',
                    'city' => 'San Francisco',
                    'state' => 'CA',
                    'postal_code' => '94103',
                    'country' => 'US',
                ]
            ]
        ]);

        foreach ($validated['items'] as $item) {
            $product = Product::findOrFail($item['product_id']);
            $price = $product->price;
            if (!empty($item['variant_id'])) {
                $variant = ProductVariant::findOrFail($item['variant_id']);
                $price = $variant->price;
            }
            $order->items()->create([
                'product_id' => $item['product_id'],
                'product_variant_id' => $item['variant_id'] ?? null,
                'price' => $price,
                'quantity' => $item['quantity']
            ]);
        }

        return response()->json([
            'id' => $mockSessionId,
            'url' => route('checkout.simulate', ['session_id' => $mockSessionId]),
        ]);
    }

    public function simulateView(Request $request)
    {
        abort_if(app()->environment('production'), 404, 'Simulation endpoints are not available in production.');

        $sessionId = $request->query('session_id');
        $order = Order::where('stripe_session_id', $sessionId)->with('items.product', 'items.variant')->firstOrFail();

        return view('checkout.simulate', compact('order'));
    }

    public function simulatePay(Request $request)
    {
        abort_if(app()->environment('production'), 404, 'Simulation endpoints are not available in production.');

        $sessionId = $request->input('session_id');
        $order = Order::where('stripe_session_id', $sessionId)->firstOrFail();

        if ($order->payment_status !== 'paid') {
            $order->update(['payment_status' => 'paid']);

            // Deduct stock
            foreach ($order->items as $item) {
                if ($item->product_variant_id) {
                    $variant = ProductVariant::find($item->product_variant_id);
                    if ($variant) {
                        $variant->inventory_quantity = max(0, $variant->inventory_quantity - $item->quantity);
                        $variant->save();
                    }
                } else {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->inventory_quantity = max(0, $product->inventory_quantity - $item->quantity);
                        
                        // Update status
                        if ($product->inventory_quantity <= 0) {
                            $product->inventory_status = 'out_of_stock';
                        } elseif ($product->inventory_quantity <= 5) {
                            $product->inventory_status = 'low_stock';
                        } else {
                            $product->inventory_status = 'in_stock';
                        }
                        $product->save();
                    }
                }
            }
        }

        $htmlContent = '
            <!DOCTYPE html>
            <html>
            <head>
                <script src="https://cdn.tailwindcss.com"></script>
                <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;700&display=swap" rel="stylesheet">
            </head>
            <body class="bg-[#0B0E14] text-white font-sans min-h-screen flex items-center justify-center p-6">
                <div class="text-center space-y-4">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-[#10B981] text-white text-3xl font-bold mb-2">✓</div>
                    <h1 class="text-2xl font-bold">Payment Completed!</h1>
                    <p class="text-slate-400">Order #' . $order->id . ' has been successfully paid. Inventory updated.</p>
                    <p class="text-xs text-[#2E62FF]">Redirecting back shortly...</p>
                    <script>
                        setTimeout(() => {
                            window.close();
                        }, 3000);
                    </script>
                </div>
            </body>
            </html>
        ';

        return response($htmlContent)->header('Content-Type', 'text/html');
    }

    public function simulateCancel(Request $request)
    {
        abort_if(app()->environment('production'), 404, 'Simulation endpoints are not available in production.');

        $sessionId = $request->input('session_id');
        $order = Order::where('stripe_session_id', $sessionId)->firstOrFail();
        $order->update(['payment_status' => 'failed']);

        $htmlContent = '
            <!DOCTYPE html>
            <html>
            <head>
                <script src="https://cdn.tailwindcss.com"></script>
                <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;700&display=swap" rel="stylesheet">
            </head>
            <body class="bg-[#0B0E14] text-white font-sans min-h-screen flex items-center justify-center p-6">
                <div class="text-center space-y-4">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-[#EF4444] text-white text-3xl font-bold mb-2">✕</div>
                    <h1 class="text-2xl font-bold text-[#EF4444]">Payment Failed</h1>
                    <p class="text-slate-400">Checkout session for order #' . $order->id . ' was cancelled or failed.</p>
                    <p class="text-xs text-slate-500">Redirecting back shortly...</p>
                    <script>
                        setTimeout(() => {
                            window.close();
                        }, 3000);
                    </script>
                </div>
            </body>
            </html>
        ';

        return response($htmlContent)->header('Content-Type', 'text/html');
    }
}
