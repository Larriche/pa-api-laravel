<?php

namespace App\Http\ExpensesTracker\Queries;

use Auth;
use Illuminate\Http\Request;
use App\Services\QueryBuilder;
use App\Domain\ExpensesTracker\Models\IncomeSource;

class IncomeSourcesQuery extends QueryBuilder
{
    /**
     * The model to be used for this service.
     *
     * @var \App\Domain\ExpensesTracker\Models\IncomeSource
     */
    protected $model = IncomeSource::class;

    /**
     * Show the resource with all its relations
     *
     * @var bool
     */
    protected $showWithRelations = false;

    public function applyFilters($query, $data)
    {
        if (Auth::user()) {
            return $query->where('user_id', Auth::id());
        }

        return $query;
    }
}
