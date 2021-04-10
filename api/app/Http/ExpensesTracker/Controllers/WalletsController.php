<?php

namespace App\Http\ExpensesTracker\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Domain\ExpensesTracker\DTO\WalletData;
use App\Domain\ExpensesTracker\Actions\CreateWalletAction;
use App\Http\ExpensesTracker\Requests\Wallets\CreateUpdateRequest;

class WalletsController extends Controller
{
    public function store(CreateUpdateRequest $request, CreateWalletAction $action): JsonResponse
    {
        $wallet = $action(WalletData::fromRequest($request));

        return response()->json($wallet, JsonResponse::HTTP_CREATED);
    }
}
