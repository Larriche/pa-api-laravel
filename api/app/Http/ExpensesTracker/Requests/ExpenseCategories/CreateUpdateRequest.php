<?php

namespace App\Http\ExpensesTracker\Requests\ExpenseCategories;

use Auth;
use App\Http\Requests\ApiRequest;

class CreateUpdateRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->getMethod() == 'PUT') {
            return Auth::id() && $this->expense_category->user_id == Auth::id();
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|unique:expense_categories,name,NULL,id,user_id,' . Auth::id(),
            'color' => 'required|unique:expense_categories,color,NULL,id,user_id,' . Auth::id(),
        ];

        if ($this->getMethod() == 'PUT') {
            $rules = [
                'name' => 'required|unique:expense_categories,name,' . $this->income_source->id
                    . ',id,user_id,' . Auth::user()->id,
                'color' => 'required|unique:expense_categories,color,' . $this->income_source->id
                    . ',id,user_id,' . Auth::user()->id
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.unique' => 'An expense category with this name already exists',
            'color.unique' => 'This color has been used for another expense category'
        ];
    }
}
