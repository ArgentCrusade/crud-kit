<?php

namespace ArgentCrusade\CrudKit\Http\Requests;

use ArgentCrusade\CrudKit\Contracts\CrudRequestInterface;
use Illuminate\Foundation\Http\FormRequest;

abstract class CrudRequest extends FormRequest implements CrudRequestInterface
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Get the request data.
     *
     * @return array
     */
    public function data()
    {
        return [];
    }
}
