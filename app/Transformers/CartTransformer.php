<?php

namespace App\Transformers;

use App\Cart;
use League\Fractal\TransformerAbstract;
use App\Transformers\LineItemTransformer;

class CartTransformer extends TransformerAbstract
{

    public function transform(Cart $cart)
    {
        return [
            'id' => (string) $cart->id,
            'customer' => (string) $cart->user_id,
            "total" => $cart->total,
            'products' => $cart->line_item ?? [],
        ];
    }
}



