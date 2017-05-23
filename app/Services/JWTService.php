<?php

namespace App\Services;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;


class JWTService
{

    public function encode($user_id)
    {  //生成TOKEN
        if (empty($user_id)) {

            throw new \Exception("No found user_id", 404);

        }

        $token = (new Builder())->setIssuer(config('APP.URL')) // Configures the issuer (iss claim)
                                ->setAudience(config('APP.URL')) // Configures the audience (aud claim)
                                ->setId(config('APP.KEY'), true) // Configures the id (jti claim), replicating as a header item
                                ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
                                ->setExpiration(time() + 3600*24*7) // Configures the expiration time of the token (nbf claim)
                                ->set('user_id', $user_id) // Configures a new claim, called "uid"
                                ->getToken(); // Retrieves the generated token

        return $token;
    }

    public function decode($token)
    {
        $token = (new Parser())->parse((string) $token); // Parses from a string

        $data = new ValidationData(); // It will use the current time to validate (iat, nbf and exp)
        $data->setIssuer(config('APP.URL'));
        $data->setAudience(config('APP.URL'));
        $data->setId(config('APP.KEY'));

        if ($token->validate($data) === false) {
            throw new \Exception('token expired', 401);
        }

        return $token->getClaim("user_id");

    }
}


