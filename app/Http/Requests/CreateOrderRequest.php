<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    public function rules()
    {
        return [
            'customer_id' => 'required|integer',
            'items' => 'required|array|min:1',
            'items.*' => 'required|integer|min:1'
        ];
    }
}
