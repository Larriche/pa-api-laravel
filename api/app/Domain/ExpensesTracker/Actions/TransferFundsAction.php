<?php

namespace App\Domain\ExpensesTracker\Actions;

use DB;
use App\Domain\ExpensesTracker\Models\Wallet;
use App\Domain\ExpensesTracker\Models\Income;
use App\Domain\ExpensesTracker\Models\Expense;
use App\Domain\ExpensesTracker\Models\IncomeSource;
use App\Domain\ExpensesTracker\Models\ExpenseCategory;
use App\Domain\ExpensesTracker\DTO\WalletTransferData;

class TransferFundsAction
{
    public function __invoke(WalletTransferData $data): array
    {
        DB::beginTransaction();

        $source_wallet = Wallet::find($data->source_id);
        $destination_wallet = Wallet::find($data->destination_id);

        $source_money = $source_wallet->balance - $data->amount;
        $destination_money = $destination_wallet->balance + $data->amount;

        $source_wallet->update([
            'balance' => $source_money
        ]);

        $destination_wallet->update([
            'balance' => $destination_money
        ]);

        // Log transfers as trackable incomes and expenses
        $transfer_category = IncomeSource::where('name', 'Transfers')->first();

        $income = Income::create([
            'income_source_id' => $transfer_category ? $transfer_category->id : null,
            'time_received' => $data->date,
            'user_id' => $data->user_id,
            'amount' => $data->amount,
            'description' => 'Transfer from ' . $source_wallet->name
        ]);

        $transfer_category = ExpenseCategory::where('name', 'Transfers')->first();

        $expense = Expense::create([
            'expense_category_id' => $transfer_category ? $transfer_category->id : null,
            'time_made' => $data->date,
            'user_id' => $data->user_id,
            'amount' => $data->amount,
            'description' => 'Transfer to ' . $destination_wallet->name
        ]);

        DB::commit();

        return [$source_wallet, $destination_wallet];
    }
}
