<?php

namespace App\Services;

use MikeMcLin\WpPassword\Facades\WpPassword;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class CustomerService
{
    protected $user;

    protected $user_id;

    public function __construct(UserRepository $user)
    {
        $this->user_id = Auth::user()->ID;
        $this->user = $user;
    }

    public function current($user_id)
    {
        return $this->user->find($user_id);
    }

    public function core($data)
    {
        $data_key = array_keys($data);
        foreach ($data_key as $row) {
            if ($row == 'user_pass') {
                $old_password = $data['old_pass'] ?? null;
                $hashed_password = $this->user->find($this->user_id)->user_pass;
                if (WpPassword::check($old_password, $hashed_password)) {
                    $password = WpPassword::make($data[$row]);
                    $this->user->updateUser($this->user_id, $row, $password);
                } else {
                    throw new \Exception('Original password authentication failed!');
                }
                unset($data['old_pass']);
                unset($data[$row]);
            } elseif ($row == 'phone_number' || $row == 'sex') {
                $this->user->updateUserMeta($this->user_id, $row, $data[$row]);
                unset($data[$row]);
            } elseif ($row == 'user_nicename') {
                $this->user->updateUser($this->user_id, $row, $data[$row]);
            } elseif ($row == 'photo' ) {
                $file = $data[$row];
                $file_ex = $file->getClientOriginalExtension(); //Picture format
                $saveName = str_random(20).".".$file_ex;  //Save picture name
                $file->move('./uploads/'.date('Y_m_d'), $saveName);
                $this->user->updateUser($this->user_id, 'user_url', '/uploads/'.date('Y_m_d').'/'.$saveName);
            }
        }
    }
}