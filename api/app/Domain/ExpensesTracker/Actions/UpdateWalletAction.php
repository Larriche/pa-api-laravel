<?php

namespace App\Domain\ExpensesTracker\Actions;

use App\Domain\ExpensesTracker\Models\Wallet;
use App\Domain\ExpensesTracker\DTO\WalletData;

class UpdateWalletAction
{
    public function __invoke(Wallet $wallet, WalletData $data): bool
    {
        return $wallet->update([
            'name' => $data->name,
            'color' => $data->color
        ]);
    }
}
