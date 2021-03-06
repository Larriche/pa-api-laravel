<?php

namespace App\Http\ExpensesTracker\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Domain\ExpensesTracker\Models\IncomeSource;
use App\Domain\ExpensesTracker\DTO\IncomeSourceData;
use App\Http\ExpensesTracker\Queries\IncomeSourcesQuery;
use App\Domain\ExpensesTracker\Actions\CreateIncomeSourceAction;
use App\Domain\ExpensesTracker\Actions\UpdateIncomeSourceAction;
use App\Domain\ExpensesTracker\Actions\DeleteIncomeSourceAction;
use App\Http\ExpensesTracker\Requests\IncomeSources\CreateUpdateRequest;
use App\Http\ExpensesTracker\Requests\IncomeSources\IncomeSourceShowRequest;

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

    public function show(IncomeSource $income_source, IncomeSourceShowRequest $request): JsonResponse
    {
        return response()->json($income_source, JsonResponse::HTTP_OK);
    }

    public function update(
        IncomeSource $income_source,
        CreateUpdateRequest $request,
        UpdateIncomeSourceAction $action
    ): JsonResponse {
        $action($income_source, IncomeSourceData::fromRequest($request));

        return response()->json($income_source, JsonResponse::HTTP_OK);
    }

    public function destroy(
        IncomeSource $income_source,
        IncomeSourceShowRequest $request,
        DeleteIncomeSourceAction $action
    ): JsonResponse {
        $action($income_source);

        return response()->json([], JsonResponse::HTTP_NO_CONTENT);
    }
}
