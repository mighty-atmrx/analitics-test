<?php

namespace App\Http\Requests\Token;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'account_id' => 'required|integer|exists:accounts,id',
            'api_service_id' => 'required|integer|exists:api_services,id',
            'token_type_id' => 'required|integer|exists:token_types,id',
            'login' => 'nullable|string|required_if:token_type_id,3',
            'password' => 'nullable|string|required_if:token_type_id,3',
        ];
    }
}
