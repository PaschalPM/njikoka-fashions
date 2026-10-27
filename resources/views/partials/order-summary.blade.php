<ul class="grid gap-4 rounded-2xl bg-linen p-6 text-sm sm:grid-cols-4">
    <li>Order number:<br><strong class="text-cocoa">{{ $order->number }}</strong></li>
    <li>Date:<br><strong class="text-cocoa">{{ $order->created_at->format('F j, Y') }}</strong></li>
    <li>Total:<br><strong class="text-cocoa">{{ $order->formattedTotal() }}</strong></li>
    <li>Status:<br><strong class="text-cocoa">{{ ucfirst($order->status) }}</strong></li>
</ul>

<table class="mt-8 w-full text-sm">
    <thead>
        <tr class="border-b border-sand text-left"><th class="py-3">Product</th><th class="py-3 text-right">Total</th></tr>
    </thead>
    <tbody>
        @foreach ($order->items as $item)
            <tr class="border-b border-sand">
                <td class="py-3">{{ $item->name }} <strong>× {{ $item->quantity }}</strong></td>
                <td class="py-3 text-right">${{ number_format($item->price_cents * $item->quantity / 100, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr><th class="py-3 text-left">Total:</th><th class="py-3 text-right text-forest">{{ $order->formattedTotal() }}</th></tr>
    </tfoot>
</table>

<div class="mt-8 text-sm text-ink/80">
    <h3 class="text-lg">Billing address</h3>
    <address class="mt-2 not-italic">
        {{ $order->first_name }} {{ $order->last_name }}<br>
        {{ $order->address_line1 }}@if ($order->address_line2), {{ $order->address_line2 }}@endif<br>
        {{ $order->city }}, {{ $order->state }} {{ $order->postcode }}<br>
        {{ $order->country }}<br>
        {{ $order->email }}@if ($order->phone)<br>{{ $order->phone }}@endif
    </address>
</div>
