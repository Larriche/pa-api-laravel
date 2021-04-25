<?php

namespace App\Http\ExpensesTracker\Requests\Wallets;

use Auth;
use App\Http\Requests\ApiRequest;
use App\Domain\ExpensesTracker\Models\Wallet;

class TransferRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::id() && $this->userOwnsWallets();
    }

    public function rules()
    {
        return [
            'destination_id' => 'required|exists:wallets,id',
            'source_id' => 'required|exists:wallets,id',
            'amount' => 'required|numeric',
            'date' => 'required|date'
        ];
    }

    private function userOwnsWallets()
    {
        $destination_wallet = Wallet::find($this->destination_id);
        $source_wallet = Wallet::find($this->source_id);

        return ($destination_wallet && $source_wallet
            && $destination_wallet->user_id == Auth::id()
            && $source_wallet->user_id == Auth::id());
    }
}
