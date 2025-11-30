<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendOrderEmailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_email' => ['required', 'email'],
            'order_id' => ['required'],
            'total_amount' => ['required', 'numeric'],
            'items' => ['required', 'array'],
            'items.*.product_id' => ['sometimes', 'integer'],
            'items.*.name' => ['sometimes', 'string'],
            'items.*.quantity' => ['sometimes', 'integer'],
            'items.*.price' => ['sometimes', 'numeric'],
        ];
    }
}

