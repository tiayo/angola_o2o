<?php

namespace App\Transformers;

use App\LineItem;
use League\Fractal\TransformerAbstract;

class LineItemTransformer extends TransformerAbstract
{

    public function transform(LineItem $item)
    {
        $keys = ['name', 'price', 'quantity', 'return', 'cost', 'item'];
        $origin_qty = absInt($item->meta['origin_qty'] ?? 0);
        $confirm_qty = absInt( $item->meta['_qty'] ?? 0);
        return [
            'property' => $keys,
             'product' => [
                    'name' => $item->name,
                    'price' => number_format($item->meta['_price'],2) ?? '0.00',
                    'quantity' => (string)$origin_qty,
                    'return' => (string)($origin_qty - $confirm_qty),
                    'cost' => number_format($item->meta['_line_total'],2),
                    'item' => $item->order_item_name,
                ],
            'image' => $item->avatar,
        ];
    }
}