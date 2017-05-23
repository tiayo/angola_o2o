<?php

namespace App\Transformers;

use App\Address;
use League\Fractal\TransformerAbstract;

class AddressTransformer extends TransformerAbstract
{

    public function transform(Address $address)
    {
        return [
            'id' =>$address->id,
            'address' => $address->address,
            'first_name' => $address->first_name,
            'last_name' => $address->last_name,
            'phone_number' => $address->phone_number,
            'is_default' => $address->is_default,
            'country' => $address->country,
            'state' => $address->state,
            'city' => $address->city,
            'user_id' => $address->user_id,
        ];
    }
}



