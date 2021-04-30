<?php

namespace App\Domain\ExpensesTracker\Actions;

use App\Domain\ExpensesTracker\Models\ExpenseCategory;
use App\Domain\ExpensesTracker\DTO\ExpenseCategoryData;

class CreateExpenseCategoryAction
{
    public function __invoke(ExpenseCategoryData $data): ExpenseCategory
    {
        return ExpenseCategory::create([
            'name' => $data->name,
            'color' => $data->color,
            'user_id' => $data->user_id
        ]);
    }
}
