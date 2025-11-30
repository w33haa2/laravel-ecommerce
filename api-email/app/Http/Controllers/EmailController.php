<?php

namespace App\Http\Controllers;

use App\Actions\SendOrderEmailAction;
use App\Http\Requests\SendOrderEmailRequest;
use Illuminate\Http\JsonResponse;

class EmailController extends Controller
{
    /**
     * Send order confirmation email.
     */
    public function sendOrderEmail(
        SendOrderEmailRequest $request,
        SendOrderEmailAction $sendOrderEmailAction
    ): JsonResponse {
        $sendOrderEmailAction->execute($request->validated());

        return response()->json(['message' => 'Email sent successfully']);
    }
}
