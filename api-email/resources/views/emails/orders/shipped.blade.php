<x-mail::message>
# Order Shipped

Your order #{{ $order['order_id'] }} has been shipped!

Total Amount: ${{ $order['total_amount'] }}

## Items:
@foreach ($order['items'] as $item)
- {{ $item['name'] ?? 'Product #' . $item['product_id'] }} (Qty: {{ $item['quantity'] }}) - ${{ $item['price'] }}
@endforeach

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
