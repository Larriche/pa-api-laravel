<?php

namespace App\Domain\ExpensesTracker\Actions;

use App\Domain\ExpensesTracker\Models\Wallet;

class DeleteWalletAction
{
    public function __invoke(Wallet $wallet)
    {
        $wallet->delete();
    }
}
