<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TransferFormConfirmValidate extends FormRequest
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
        $authUser = Auth::guard('web')->user();
        $authUser_wallet = $authUser->wallet->amount;
        return [
            'phone' => "required|exists:users,phone|not_in:$authUser->phone",
            'amount' => "required|gte:500|lte:" . $authUser_wallet,
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => "Please fill out the recipient's account information.",
            'phone.not_in' => 'Ahh, R U Fool',
            'amount.required' => 'Please fill the amount.',
            'amount.gte' => 'The amount must be at least 500 MMK.',
            'amount.lte' => "You Don't have enough money.",
        ];
    }
}
