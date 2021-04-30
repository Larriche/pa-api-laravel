<?php

namespace App\Domain\ExpensesTracker\DTO;

use Auth;
use Spatie\DataTransferObject\DataTransferObject;
use App\Domain\ExpensesTracker\Models\ExpenseCategory;
use App\Http\ExpensesTracker\Requests\ExpenseCategories\CreateUpdateRequest;

class ExpenseCategoryData extends DataTransferObject
{
    /**
     * Name of expense category
     *
     * @var string
     */
    public $name;

    /**
     * Hex code of color to identify expense category by
     *
     * @var string
     */
    public $color;

    /**
     * Id of user who created expense category
     *
     * @var int
     */
    public $user_id;

     /**
     * Create a new application DTO based on data given
     *
     * @param array $data Data for creating DTO
     * @return \App\Domain\ExpensesTracker\DTO\ExpenseCategoryData
     */
    public static function new($data): ExpenseCategoryData
    {
        return new self($data);
    }

    /**
     * Pull data for creating or updating resource from a request body
     *
     * @param \App\Http\ExpensesTracker\Requests\ExpenseCategories\CreateUpdateRequest $request The request
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
