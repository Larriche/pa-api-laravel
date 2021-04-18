<?php

namespace App\Domain\ExpensesTracker\Actions;

use App\Domain\ExpensesTracker\Models\IncomeSource;
use App\Domain\ExpensesTracker\DTO\IncomeSourceData;

class UpdateIncomeSourceAction
{
    public function __invoke(IncomeSource $income_source, IncomeSourceData $data): bool
    {
        return $income_source->update([
            'name' => $data->name,
            'color' => $data->color
        ]);
    }
}
