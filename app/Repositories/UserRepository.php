<?php

namespace App\Repositories;

use App\User;
use App\UserMeta;

class UserRepository implements UserRepositoryInterface
{
        protected $user;
        protected $user_meta;

        public function __construct(User $user, UserMeta $user_meta)
        {
            $this->user = $user;
            $this->user_meta = $user_meta;
        }

        public function getByEmail($email)
        {
            return $this->user->where('user_email', $email)->get();
        }

        public function byEmail($email)
        {
            return $this->user->where('user_email', $email)->firstOrFail();
        }

        public function byFacebook($facebook)
        {
            $meta = $this->user_meta->where('meta_key', 'facebook_id')->where('meta_value', $facebook)->first();
            return $this->findMeta($meta->user_id, 'facebook_id');
        }

        public function find($id)
        {
            return $this->user->findOrFail($id);
        }

        public function findMeta($id, $value)
        {
            return $this->user_meta->where('user_id', $id)->where('meta_key', $value)->first();
        }

        public function registerEmail($data)
        {
            return $this->user->create($data);
        }

        public function registerFacebook($data)
        {
            return $this->user_meta->create($data);
        }

        public function updateUser($user_id, $key, $value)
        {
            $flight = $this->user->find($user_id);
            $flight[$key] = $value;
            $flight->save();
        }

        public function updateUserMeta($user_id, $key, $value)
        {
            $flight = $this->user_meta->where('user_id',$user_id)->where('meta_key',$key)->first();

            if(empty($flight)){
                $map['user_id'] = $user_id;
                $map['meta_key'] = $key;
                $map['meta_value'] = $value;
                $this->user_meta->create($map);

            }else{
                $flight->meta_value = $value;
                $flight->save();
                return $value;

            }
        }
}