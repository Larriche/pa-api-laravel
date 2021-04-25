<?php

namespace App\Http\ExpensesTracker\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Domain\ExpensesTracker\Models\Wallet;
use App\Domain\ExpensesTracker\DTO\WalletData;
use App\Http\ExpensesTracker\Queries\WalletsQuery;
use App\Domain\ExpensesTracker\DTO\WalletTransferData;
use App\Domain\ExpensesTracker\Actions\CreateWalletAction;
use App\Domain\ExpensesTracker\Actions\UpdateWalletAction;
use App\Domain\ExpensesTracker\Actions\DeleteWalletAction;
use App\Domain\ExpensesTracker\Actions\TransferFundsAction;
use App\Http\ExpensesTracker\Requests\Wallets\TransferRequest;
use App\Http\ExpensesTracker\Requests\Wallets\WalletShowRequest;
use App\Http\ExpensesTracker\Requests\Wallets\CreateUpdateRequest;

class WalletsController extends Controller
{
    public function index(Request $request, WalletsQuery $query): JsonResponse
    {
        $wallets = $query->execute($request);

        return response()->json($wallets, JsonResponse::HTTP_OK);
    }

    public function store(CreateUpdateRequest $request, CreateWalletAction $action): JsonResponse
    {
        $wallet = $action(WalletData::fromRequest($request));

        return response()->json($wallet, JsonResponse::HTTP_CREATED);
    }

    public function show(
        Wallet $wallet,
        WalletShowRequest $request
    ): JsonResponse {
        return response()->json($wallet, JsonResponse::HTTP_OK);
    }

    public function update(
        Wallet $wallet,
        CreateUpdateRequest $request,
        UpdateWalletAction $action
    ): JsonResponse {
        $action($wallet, WalletData::fromRequest($request));

        return response()->json($wallet, JsonResponse::HTTP_OK);
    }

    public function destroy(
        Wallet $wallet,
        WalletShowRequest $request,
        DeleteWalletAction $action
    ): JsonResponse {
        $action($wallet);

        return response()->json([], JsonResponse::HTTP_NO_CONTENT);
    }

    public function transferFunds(TransferRequest $request, TransferFundsAction $action)
    {
        $wallets = $action(WalletTransferData::fromRequest($request));

        return response()->json($wallets, JsonResponse::HTTP_OK);
    }
}
