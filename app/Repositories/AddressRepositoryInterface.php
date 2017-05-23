<?php

namespace App\Repositories;

interface AddressRepositoryInterface
{
    public function insert($data);

    public function byUser($user_id);

    public function update($id, $data);

    public function updateDefault($user_id);

    public function delete($id);

    public function  find($user_id);
}