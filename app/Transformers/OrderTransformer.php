<?php

namespace App\Transformers;

use App\Order;
use League\Fractal\TransformerAbstract;
use App\Transformers\LineItemTransformer;
use App\Transformers\ShippingItemTransformer;

class OrderTransformer extends TransformerAbstract
{

    public function transform(Order $order)
    {
        $urgent =$order->meta['_urgent'] ?? false;
        $urgent = boolval($urgent);
        $total = $order->meta['_order_total'] ?? 0;
        $total = number_format($total, 2);

        $order_status_names =['wc-pending' => 'wait-delivering', 'wc-processing' => 'delivering', 'wc-on-hold' => 'delivered', 'wc-completed' => 'completed',
            'wc-cancelled' => 'cancelled', 'wc-refunded' => 'refunded', 'wc-failed' => 'failed'];
        return [
            'id' => (string) $order->ID,
            'date' => $order->post_date->format('H:m m/d Y'),
            'status' => $order_status_names[$order->post_status] ?? 'error',
            'customer_id' => $order->meta['_customer_user'],
            'currency' => $order->meta['_order_currency'],
            'urgent' => $urgent,
            'shipping_fee' => $urgent ? number_format(floatval(env('urgentShippingFee',10)),2) : '0.00',
            "order_total" => $total,
            'line_items' => fractal($order->lineItems, new LineItemTransformer())->toArray()['data'],
            'deliver_man' => $order->meta['order_deliverer'] ?? '',
            'address' => $order->meta['shipping'] ?? [],
        //    'shipping_lines' => fractal($order->shippingItems, new ShippingItemTransformer())->toArray()['data'],
        ];
    }
}



