<?php

namespace App\Repositories;

interface UserRepositoryInterface
{
        public function getByEmail($email);
        
        public function byEmail($email);

        public function byFacebook($facebook);

        public function find($id);

        public function findMeta($id,$value);

        public function registerEmail($data);

        public function registerFacebook($data);

        public function updateUser($user_id, $key, $value);

        public function updateUserMeta($user_id, $key, $value);
}