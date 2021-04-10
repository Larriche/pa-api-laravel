<?php

namespace App\Http\ExpensesTracker\Requests\Wallets;

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
            'name' => 'required|unique:wallets,name,NULL,id,user_id,' . Auth::id(),
            'color' => 'required|unique:wallets,color,NULL,id,user_id,' . Auth::id(),
        ];

        if ($this->getMethod() == 'PUT') {
            $rules = [
                'name' => 'required|unique:wallets,name,' . $this->route('wallet_id')
                    . ',id,user_id,' . Auth::user()->id,
                'color' => 'required|unique:wallets,color,' . $this->route('wallet_id')
                    . ',id,user_id,' . Auth::user()->id
            ];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.unique' => 'A wallet with this name already exists',
            'color.unique' => 'This color has been used for another wallet'
        ];
    }
}
