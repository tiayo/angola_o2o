<?php

namespace App\Transformers;

use App\ShippingItem;
use League\Fractal\TransformerAbstract;

class ShippingItemTransformer extends TransformerAbstract
{

    public function transform(ShippingItem $item)
    {
        return[
            'id' => $item->order_item_id,
            'method_title' => $item->order_item_name,
            'method_id' => $item->meta['method_id'],
            'total' => $item->meta['cost'],
        ];
    }
}