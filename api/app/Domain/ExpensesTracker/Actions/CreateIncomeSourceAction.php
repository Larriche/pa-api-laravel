<?php

namespace App\Domain\ExpensesTracker\Actions;

use App\Domain\ExpensesTracker\Models\IncomeSource;
use App\Domain\ExpensesTracker\DTO\IncomeSourceData;

class CreateIncomeSourceAction
{
    public function __invoke(IncomeSourceData $data): IncomeSource
    {
        return IncomeSource::create([
            'name' => $data->name,
            'color' => $data->color,
            'user_id' => $data->user_id
        ]);
    }
}
