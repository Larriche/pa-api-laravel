<?php

namespace App\Http\ExpensesTracker\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Domain\ExpensesTracker\Models\IncomeSource;
use App\Domain\ExpensesTracker\DTO\IncomeSourceData;
use App\Http\ExpensesTracker\Queries\IncomeSourcesQuery;
use App\Domain\ExpensesTracker\Actions\CreateIncomeSourceAction;
use App\Http\ExpensesTracker\Requests\IncomeSources\CreateUpdateRequest;

class IncomeSourcesController extends Controller
{
    public function index(Request $request, IncomeSourcesQuery $query): JsonResponse
    {
        $income_sources = $query->execute($request);

        return response()->json($income_sources, JsonResponse::HTTP_OK);
    }

    public function store(CreateUpdateRequest $request, CreateIncomeSourceAction $action): JsonResponse
    {
        $source = $action(IncomeSourceData::fromRequest($request));

        return response()->json($source, JsonResponse::HTTP_CREATED);
    }
}
