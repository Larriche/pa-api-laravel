<?php

namespace App\Domain\ExpensesTracker\Actions;

use App\Domain\ExpensesTracker\Models\Wallet;
use App\Domain\ExpensesTracker\DTO\WalletData;

class CreateWalletAction
{
    public function __invoke(WalletData $data): Wallet
    {
        return Wallet::create([
            'name' => $data->name,
            'color' => $data->color,
            'user_id' => $data->user_id
        ]);
    }
}
