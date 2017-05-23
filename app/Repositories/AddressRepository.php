<?php
namespace App\Repositories;

use App\Address;

class AddressRepository implements AddressRepositoryInterface
{
    protected $address;

    public function __construct(Address $address)
    {
        $this->address = $address;
    }

    public function byUser($user_id)
    {
        return $this->address->where("user_id", $user_id)->get();
    }

    public function insert($data)
    {
        $this->address->create($data);
    }

    public function update($id, $data)
    {
        $this->address->where('id', $id)->update($data);
    }

    public function updateDefault($user_id)
    {
        $data = ['is_default' => 0];
        $this->address->where('user_id', $user_id)->update($data);
    }

    public function delete($id)
    {
        $this->address->where('id', $id)->delete();

    }

    public function  find($user_id)
    {
        return $this->address->where('user_id', $user_id)->get();
    }

}