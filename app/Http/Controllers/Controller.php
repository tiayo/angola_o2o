<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use League\Fractal\TransformerAbstract;
use Illuminate\Validation\Validator;

class Controller extends BaseController
{
    public function transform($data,  TransformerAbstract $transformer)
    {
        return fractal($data, $transformer)->toArray()['data'];
    }

    final public function error($message = '', $code = 403)
    {
        $value = ['message'=>$message];
        return response()->json($value, $code);
    }

    final public function success(array $data = [], $code = 200)
    {
        return response()->json($data, $code);
    }

    protected function formatValidationErrors(Validator $validator)
    {
        return $validator->errors()->all();
    }

}
