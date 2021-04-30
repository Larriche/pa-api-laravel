<?php

namespace App\Http\ExpensesTracker\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Domain\ExpensesTracker\Models\ExpenseCategory;
use App\Domain\ExpensesTracker\DTO\ExpenseCategoryData;
use App\Http\ExpensesTracker\Queries\ExpenseCategoriesQuery;
use App\Domain\ExpensesTracker\Actions\CreateExpenseCategoryAction;
use App\Domain\ExpensesTracker\Actions\UpdateExpenseCategoryAction;
use App\Domain\ExpensesTracker\Actions\DeleteExpenseCategoryAction;
use App\Http\ExpensesTracker\Requests\ExpenseCategories\ShowRequest;
use App\Http\ExpensesTracker\Requests\ExpenseCategories\CreateUpdateRequest;

class ExpenseCategoriesController extends Controller
{
    public function index(Request $request, ExpenseCategoriesQuery $query): JsonResponse
    {
        $expense_categories = $query->execute($request);

        return response()->json($expense_categories, JsonResponse::HTTP_OK);
    }

    public function store(CreateUpdateRequest $request, CreateExpenseCategoryAction $action): JsonResponse
    {
        $category = $action(ExpenseCategoryData::fromRequest($request));

        return response()->json($category, JsonResponse::HTTP_CREATED);
    }
}
