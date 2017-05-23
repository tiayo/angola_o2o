<?php

namespace App\Services;

use MikeMcLin\WpPassword\Facades\WpPassword;
use App\Repositories\UserRepositoryInterface;

class LoginService
{
    protected $user;
    protected $jwt;

    public function __construct(UserRepositoryInterface $user,JWTService $jwt)
    {
        $this->user = $user;
        $this->jwt = $jwt;
    }

    public function byEmail($email, $password)
    {
        $total = $this->user->getByEmail($email);

        if (count($total) == 0 || count($total) > 1) {
            throw new \Exception('logoin error');
        }

        $user_info = $this->user->ByEmail($email);
        $hashed_password = $user_info->user_pass;

        if (WpPassword::check($password, $hashed_password)) {
            return $user_info;
        }

        throw new \Exception('password error');
    }

    public function byFacebook($facebook)
    {
        try {
            $facebbook_info = $this->user->byFacebook($facebook);
        } catch (\Exception $e) {
            $user_info = $this->user->registerEmail(['user_registered'=>date('Y-m-d H:i:s')]);
            $data = ['meta_key'=>'facebook_id', 'meta_value'=>$facebook, 'user_id'=>$user_info->ID];
            $facebbook_info = $this->user->registerFacebook($data);
        }
        return $facebbook_info;

    }

    public function byToken($token)
    {
        $user_id = $this->jwt->decode($token);
        return $this->user->find($user_id);
    }
}