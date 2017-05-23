<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{

        public function transform(User $user)
        {
            $address_array = $user->address->where('is_default',1)->first();
            $address_array = empty($address_array)?null:$address_array->toArray();
            
                return [
                    'id' => (string) $user->ID,
                    'email' => $user->user_email,
                    'username' => $user->user_nicename ?? '',
                    'phone_number' => $user->meta['phone_number'] ?? '',
                    'sex' => $user->meta['sex'] ?? '',
                    'user_url' => $user->user_url ??'',
                    'address' => [
                        'address' => $address_array['address'] ?? '',
                        'first_name' => $address_array['first_name'] ?? '',
                        'last_name' => $address_array['last_name'] ?? '',
                        'phone_number' => $address_array['phone_number'] ?? '',
                        'country' => $address_array['country'] ?? '',
                        'state' => $address_array['state'] ?? '',
                        'city' => $address_array['city'] ?? ''
                    ]
                ];
        }
}



