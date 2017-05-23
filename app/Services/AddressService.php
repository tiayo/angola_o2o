<?php

namespace App\Services;

use App\Repositories\AddressRepository;
use App\Exceptions\MyException;


class AddressService
{
    protected $address;

    public function __construct(AddressRepository $adress)
    {
        $this->address = $adress;
    }

    public function insert($user_id, $data)
    {
        $limit = 3;//Limit the maximum number of addresses
        if (!isset($data['user_id'])) {
            $data['user_id'] = $user_id;
        }

        $addresses = $this->address->byUser($user_id);

        $total = count($addresses);

        if ($total >= $limit) {
            throw new \Exception('address too much !');
        }

        $new = $this->address->insert($data);

        $addresses->push($new);

        return $addresses;
    }

    public function update($id, $user_id, $data)
    {
        $value = $data['is_default'] ?? 0;
        if ($value == 1) {
            $this->address->updateDefault($user_id);
        }
        $this->address->update($id, $data);
    }

    public function delete($id)
    {
        $this->address->delete($id);
    }

    public function find($user_id){
        return $this->address->find($user_id);
    }

}
