<?php

namespace App\Http\Requests;

use App\Enums\ProductStatuses;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'price' => ['nullable', 'numeric'],
            'status' => ['nullable', new Enum(ProductStatuses::class)],
            'latest_price_checked_at' => ['nullable', 'date_format:Y-m-d H:i:s'],
            'external_id' => ['nullable', 'numeric'],
        ];
    }
}
