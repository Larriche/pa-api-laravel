<?php

namespace App\Http\ExpensesTracker\Requests\IncomeSources;

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
            return Auth::id() && $this->income_source->user_id == Auth::id();
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
            'name' => 'required|unique:income_sources,name,NULL,id,user_id,' . Auth::id(),
            'color' => 'required|unique:income_sources,color,NULL,id,user_id,' . Auth::id(),
        ];

        if ($this->getMethod() == 'PUT') {
            $rules = [
                'name' => 'required|unique:income_sources,name,' . $this->income_source->id
                    . ',id,user_id,' . Auth::user()->id,
                'color' => 'required|unique:income_sources,color,' . $this->income_source->id
                    . ',id,user_id,' . Auth::user()->id
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.unique' => 'An income source with this name already exists',
            'color.unique' => 'This color has been used for another income source'
        ];
    }
}
