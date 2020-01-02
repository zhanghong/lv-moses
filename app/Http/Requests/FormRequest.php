<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as BaseRequest;

class FormRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }
}
