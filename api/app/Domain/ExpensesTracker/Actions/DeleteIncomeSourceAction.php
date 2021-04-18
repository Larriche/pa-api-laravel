<?php

namespace App\Domain\ExpensesTracker\Actions;

use App\Domain\ExpensesTracker\Models\IncomeSource;

class DeleteIncomeSourceAction
{
    public function __invoke(IncomeSource $income_source)
    {
        $income_source->delete();
    }
}
