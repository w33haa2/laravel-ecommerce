<?php

namespace App\Actions;

use App\Mail\OrderShipped;
use Illuminate\Support\Facades\Mail;

class SendOrderEmailAction
{
    /**
     * Send order confirmation email to customer.
     *
     * @param  array<string, mixed>  $orderData
     * @return void
     */
    public function execute(array $orderData): void
    {
        Mail::to($orderData['customer_email'])
            ->send(new OrderShipped($orderData));
    }
}

