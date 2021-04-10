<?php

namespace App\Domain\ExpensesTracker\DTO;

use Auth;
use Spatie\DataTransferObject\DataTransferObject;
use App\Domain\ExpensesTracker\Models\Wallet;
use App\Http\ExpensesTracker\Requests\Wallets\CreateUpdateRequest;

class WalletData extends DataTransferObject
{
    /**
     * Name of wallet
     *
     * @var string
     */
    public $name;

    /**
     * Hex code of color to identify wallet by
     *
     * @var string
     */
    public $color;

    /**
     * Id of user who created wallet
     *
     * @var int
     */
    public $user_id;

     /**
     * Create a new application DTO based on data given
     *
     * @param array $data Data for creating DTO
     * @return \App\Domain\ExpensesTracker\DTO\WalletData
     */
    public static function new($data): ApplicationData
    {
        return new self($data);
    }

    /**
     * Pull data for creating or updating an application from a request body
     *
     * @param \App\Http\ExpensesTracker\Requests\Wallets\CreateUpdateRequest $request The request
     */
    public static function fromRequest(CreateUpdateRequest $request)
    {
        return new self([
            'name' => $request->name,
            'color' => $request->color,
            'user_id' => $request->user()->id
        ]);
    }
}
