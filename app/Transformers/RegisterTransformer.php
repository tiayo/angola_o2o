<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;
use App\Services\JWTService;

class RegisterTransformer extends TransformerAbstract
{
    protected $jwt;

    public function __construct(JWTService $jwt)
    {
        $this->jwt = $jwt;
    }

    public function transform(User $user)
    {
        return [
            "token" => (string)$this->jwt->encode($user->ID),
            "email" => $user->user_email ??'',
            'user_nicename' => $user->user_nicename ??'',
            'user_dispaly_name' => $user->dispaly_name ??'',
            'user_url' => $user->user_url ??''
        ];
    }
}

