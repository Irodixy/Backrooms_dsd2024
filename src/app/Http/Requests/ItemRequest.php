<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ItemRequest extends FormRequest
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
            'name' => 'required|string|max:30',
            'price' => 'required|numeric',
            'description' => 'required|string|max:100',
            'type' => 'required|string|max:20',
            'quantity' => 'required|integer',
            'imgName' => 'sometimes|string|max:255',
            'IDStore' => 'required|exists:store,id',
            'costumerVisits' => 'required|integer'
        ];
    }
}
