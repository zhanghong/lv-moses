<?php

namespace App\Http\Requests\Shop;

use Illuminate\Validation\Rule;
use App\Http\Requests\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class BrandRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => [
                'required',
                'min:2',
                'max:20',
                Rule::unique('shop_brands')->where(function ($query) {
                    if ($this->route('brand')) {
                        $query = $query->where('id', '<>', $this->route('brand')->id);
                    }
                    return $query->where('shop_id', $this->route('shop')->id)->whereNull('deleted_at');
                }),
            ],
            'description' => 'max:200',
        ];
    }

    public function failedValidation(Validator $validator) {
        exit(json_encode(array(
            'code' => 403,
            'info' => $validator->getMessageBag()->first()
        )));
    }
}
