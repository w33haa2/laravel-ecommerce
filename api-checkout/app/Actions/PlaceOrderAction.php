<?php

namespace App\Actions;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PlaceOrderAction
{
    /**
     * Place a new order.
     *
     * @param  array<string, mixed>  $data
     * @return Order
     */
    public function execute(array $data): Order
    {
        // Calculate total amount
        $totalAmount = $this->calculateTotal($data['items']);

        // Create order
        $order = Order::create([
            'customer_email' => $data['customer_email'],
            'total_amount' => $totalAmount,
        ]);

        // Create order items
        $this->createOrderItems($order, $data['items']);

        // Trigger email service
        $this->triggerEmailService($order, $data['items']);

        return $order->load('items');
    }

    /**
     * Calculate total amount from items.
     *
     * @param  array<int, array<string, mixed>>  $items
     * @return float
     */
    private function calculateTotal(array $items): float
    {
        $total = 0;

        foreach ($items as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return $total;
    }

    /**
     * Create order items for the order.
     *
     * @param  Order  $order
     * @param  array<int, array<string, mixed>>  $items
     * @return void
     */
    private function createOrderItems(Order $order, array $items): void
    {
        foreach ($items as $item) {
            $order->items()->create([
                'product_id' => $item['product_id'],
                'name' => $item['name'] ?? 'Product #' . $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }
    }

    /**
     * Trigger email service to send order confirmation.
     *
     * @param  Order  $order
     * @param  array<int, array<string, mixed>>  $items
     * @return void
     */
    private function triggerEmailService(Order $order, array $items): void
    {
        try {
            $emailServiceUrl = config('services.email.url', 'http://api-email/api/send-order-email');

            Http::post($emailServiceUrl, [
                'order_id' => $order->id,
                'customer_email' => $order->customer_email,
                'total_amount' => $order->total_amount,
                'items' => $items,
            ]);
        } catch (\Exception $e) {
            // Log error but don't fail the order
            Log::error('Failed to send email: ' . $e->getMessage());
        }
    }
}

