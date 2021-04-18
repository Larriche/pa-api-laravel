<?php

namespace App\Http\ExpensesTracker\Requests\IncomeSources;

use Auth;
use App\Http\Requests\ApiRequest;

class IncomeSourceShowRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::id() && $this->income_source->user_id == Auth::id();
    }

    public function rules()
    {
        return [];
    }
}
