<?php

namespace App\Http\ExpensesTracker\Requests\Wallets;

use Auth;
use App\Http\Requests\ApiRequest;

class WalletShowRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::id() && $this->wallet->user_id == Auth::id();
    }

    public function rules()
    {
        return [];
    }
}
