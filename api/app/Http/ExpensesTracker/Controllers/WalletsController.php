<?php

namespace App\Http\ExpensesTracker\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Domain\ExpensesTracker\DTO\WalletData;
use App\Http\ExpensesTracker\Queries\WalletsQuery;
use App\Domain\ExpensesTracker\Actions\CreateWalletAction;
use App\Http\ExpensesTracker\Requests\Wallets\CreateUpdateRequest;

class WalletsController extends Controller
{
    public function index(Request $request, WalletsQuery $query)
    {
        $wallets = $query->execute($request);

        return response()->json($wallets, JsonResponse::HTTP_OK);
    }

    public function store(CreateUpdateRequest $request, CreateWalletAction $action): JsonResponse
    {
        $wallet = $action(WalletData::fromRequest($request));

        return response()->json($wallet, JsonResponse::HTTP_CREATED);
    }
}
