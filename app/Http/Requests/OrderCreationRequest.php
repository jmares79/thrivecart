<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderCreationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'description' => 'required|string|max:255',
            'products' => 'required|array|min:1',
            'products.*.id' => 'integer|exists:products,id',
            'products.*.amount' => 'required|numeric',
        ];
    }
}
