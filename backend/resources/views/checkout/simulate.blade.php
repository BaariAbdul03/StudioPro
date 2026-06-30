<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudioPro | Simulated Payment</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Geist', sans-serif;
            background-color: #0B0E14;
        }
    </style>
</head>
<body class="text-[#dae2fd] min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-md bg-[#161B22] border border-[#434656] rounded-lg shadow-2xl overflow-hidden">
        <!-- Header -->
        <div class="p-6 border-b border-[#434656] bg-[#060e20]/60 flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-widest text-[#8d90a2]">Stripe Simulator</span>
                <h1 class="text-white text-lg font-bold mt-1">Simulated Checkout</h1>
            </div>
            <div class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></div>
        </div>

        <div class="p-6 space-y-6">
            <!-- Order Info -->
            <div class="space-y-3">
                <div class="flex justify-between items-center text-xs text-[#8d90a2] uppercase tracking-wider font-bold">
                    <span>Order Details</span>
                    <span class="font-mono text-[#2E62FF]">PENDING</span>
                </div>
                
                <div class="bg-[#0B0E14] border border-[#434656] rounded-sm p-4 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-[#8d90a2]">Customer</span>
                        <span class="font-semibold text-white">{{ $order->customer_email }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-[#8d90a2]">Session ID</span>
                        <span class="font-mono text-xs text-white truncate max-w-[180px]" title="{{ $order->stripe_session_id }}">{{ $order->stripe_session_id }}</span>
                    </div>
                    <div class="border-t border-[#434656]/30 pt-3 flex justify-between items-center">
                        <span class="text-[#8d90a2] font-medium">Total Amount</span>
                        <span class="text-white text-xl font-extrabold">${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Items -->
            <div class="space-y-2">
                <span class="text-[10px] text-[#8d90a2] uppercase tracking-wider font-bold block">Cart Items</span>
                <div class="max-h-36 overflow-y-auto space-y-2 pr-1">
                    @foreach($order->items as $item)
                    <div class="flex justify-between items-center bg-[#0B0E14]/40 border border-[#434656]/30 p-2.5 rounded-sm text-xs">
                        <div>
                            <span class="text-white font-medium">{{ $item->product->title ?? 'Product' }}</span>
                            @if($item->variant)
                            <span class="text-slate-400 block mt-0.5 text-[10px]">Variant: {{ $item->variant->title }}</span>
                            @endif
                        </div>
                        <div class="text-right">
                            <span class="text-white block font-semibold">${{ number_format($item->price, 2) }}</span>
                            <span class="text-[#8d90a2] block mt-0.5 text-[10px]">Qty: {{ $item->quantity }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Actions -->
            <div class="pt-4 border-t border-[#434656]/30 flex flex-col gap-3">
                <form action="{{ route('checkout.pay') }}" method="POST">
                    @csrf
                    <input type="hidden" name="session_id" value="{{ $order->stripe_session_id }}">
                    <button type="submit" class="w-full bg-[#2E62FF] hover:bg-blue-600 text-white font-bold py-3 px-4 rounded text-sm transition active:scale-[0.98] flex items-center justify-center gap-2 cursor-pointer">
                        Simulate Successful Payment
                    </button>
                </form>

                <form action="{{ route('checkout.cancel') }}" method="POST">
                    @csrf
                    <input type="hidden" name="session_id" value="{{ $order->stripe_session_id }}">
                    <button type="submit" class="w-full border border-[#434656] hover:bg-red-500/10 text-red-400 font-bold py-3 px-4 rounded text-sm transition active:scale-[0.98] flex items-center justify-center gap-2 cursor-pointer">
                        Simulate Payment Failure
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
