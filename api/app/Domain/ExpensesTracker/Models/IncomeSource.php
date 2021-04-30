<?php

namespace App\Domain\ExpensesTracker\Models;

use App\Models\Model;
use Database\Factories\IncomeSourceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class IncomeSource extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return IncomeSourceFactory::new();
    }
}
