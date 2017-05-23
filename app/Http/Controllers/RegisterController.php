<?php

namespace App\Http\Controllers;

use App\Services\RegisterService;
use Illuminate\Http\Request;
use MikeMcLin\WpPassword\Facades\WpPassword;
use App\Transformers\LoginTransformer;
use App\Transformers\RegisterTransformer;

class RegisterController extends Controller
{
    protected $register;
    public function __construct(RegisterService $register)
    {
        $this->register = $register;
    }

    public function byEmail(Request $request)
    {
        $this->validate($request,[
            'password'=>'required',
            'email'=>'required',
        ]);

        $password = $hashed_password = WpPassword::make($request->get('password'));
        $data = ['user_registered'=>date('Y-m-d H:i:s'),'user_pass'=>$password,'user_email'=>$request->get('email'),];

         try {
             $result = $this->register->byEmail($request->get('email'), $data);
         } catch (\Exception $e) {
             return $this->error($e->getMessage());
         }

        return $this->transform($result, app(RegisterTransformer::class));
    }

}