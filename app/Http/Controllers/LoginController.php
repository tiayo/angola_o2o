<?php

namespace App\Http\Controllers;

use App\Transformers\RegisterFacebookTransformer;
use Illuminate\Http\Request;
use App\Services\LoginService;
use App\Transformers\LoginTransformer;
use Exception;
use App\Repositories\UserRepository;

class LoginController extends Controller
{
    protected $login;

    public function __construct(LoginService $login)
    {
        $this->login = $login;
    }

    public function test(Request $request)
    {
        \Log::info(serialize($request->all()));
    }

    public function byEmail(Request $request)
    {
        $this->validate($request,[
            'email' => 'required',
            'password' => 'required',
        ]);

        try {
            $user = $this->login->byEmail($request->get('email'),$request->get('password'));
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
        return $this->transform_login($user);
    }

    public function byFacebook(Request $request, UserRepository $user_db)
    {
        $this->validate($request,[
            'facebook_id' => 'required',
        ]);
        $user_info = $this->login->byFacebook($request->get("facebook_id"));
        $user = $user_db->find($user_info['user_id']);
        return $this->transform($user, app(RegisterFacebookTransformer::class));
    }

    public function byToken(Request $request)
    {
        $this->validate($request,[
            'token' => 'required',
        ]);

        $token = $request->get('token');
        try {
            $user = $this->login->byToken($token);
        } catch (Exception $e){
            return response()->json($e->getMessage(), 410);
        }

        return $this->transform_login($user);
    }

    private function transform_login($user)
    {
        return $this->transform($user, app(LoginTransformer::class));
    }
}
