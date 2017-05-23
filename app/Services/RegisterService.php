<?php

namespace App\Services;

use App\Repositories\UserRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class RegisterService
{
    protected $user;

    public function __construct(UserRepositoryInterface $user)
    {
        $this->user = $user;
    }

    public function byEmail($email, $data)
    {
            $total = $this->user->getByEmail($email);

            if (count($total) == 0) {
                try {
                    $result = $this->user->registerEmail($data);
                } catch (\Exception $e) {
                    throw new \Exception('registered error!');
                }
                return $result;
            }

        throw new \Exception('The mailbox has been registered');
    }
}