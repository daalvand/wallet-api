<?php

namespace App\Http\Requests\Wallet;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepositMoney extends FormRequest
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
        $currentBalance = $this->route('user')->wallet->balance;
        return [
            'amount' => [
                'required', 'integer', 'gte:' . -$currentBalance,
            ],
        ];
    }
}
