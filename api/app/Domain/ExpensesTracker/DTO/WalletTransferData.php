<?php

namespace App\Domain\ExpensesTracker\DTO;

use Auth;
use App\Domain\ExpensesTracker\Models\Wallet;
use Spatie\DataTransferObject\DataTransferObject;
use App\Http\ExpensesTracker\Requests\Wallets\TransferRequest;

class WalletTransferData extends DataTransferObject
{
    /**
     * Id of source wallet
     *
     * @var int
     */
    public $source_id;

    /**
     * Id of destination wallet
     *
     * @var int
     */
    public $destination_id;

    /**
     * Amount to transfer
     *
     * @var double
     */
    public $amount;

    /**
     * Id of user making transfer
     *
     * @var int
     */
    public $user_id;

    /**
     * Date of transfer action
     *
     * @var string
     */
    public $date;

     /**
     * Create a new wallet transfer DTO based on data given
     *
     * @param array $data Data for creating DTO
     * @return \App\Domain\ExpensesTracker\DTO\WalletTransferData
     */
    public static function new($data): WalletTransferData
    {
        return new self($data);
    }

    /**
     * Pull data for making wallet transfers from a request body
     *
     * @param \App\Http\ExpensesTracker\Requests\Wallets\TransferRequest $request The request
     */
    public static function fromRequest(TransferRequest $request)
    {
        return new self([
            'destination_id' => (int) $request->destination_id,
            'source_id' => (int) $request->source_id,
            'amount' => (float) $request->amount,
            'date' => $request->date,
            'user_id' => $request->user()->id
        ]);
    }
}
