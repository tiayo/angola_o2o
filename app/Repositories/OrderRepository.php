<?php

namespace App\Repositories;
use App\Order;
use App\PostMeta;
use App\ItemMeta;
use App\LineItem;
use App\Product;
use App\ProductVariation;
use App\Address;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class OrderRepository
{
    protected $order;
    protected $orderMeta;
    protected $itemMeta;
    protected $lineItem;
    protected $product;
    protected $variation;
    protected $address;

    public function __construct(Order $order, PostMeta $orderMeta, ItemMeta $itemMeta, LineItem $lineItem,
        Product $product, ProductVariation $variation, Address $address)
    {
        $this->order = $order;
        $this->orderMeta = $orderMeta;
        $this->itemMeta = $itemMeta;
        $this->lineItem = $lineItem;
        $this->product = $product;
        $this->variation = $variation;
        $this->address = $address;
    }

    protected function updateOrderItemMeta($item_id, $meta_key, $meta_value)
    {
        $itemMeta = $this->itemMeta->where('order_item_id', $item_id)->where('meta_key', $meta_key)->first();
        if(empty($itemMeta))
        {
            $itemMeta = $this->itemMeta->create([
                'order_item_id' => $item_id,
                'meta_key' => $meta_key,
                'meta_value' => $meta_value,
            ]);
        }
        else{
            $itemMeta->meta_value = $meta_value;
            $itemMeta->save();
        }
        return $itemMeta;
    }

    protected function updatePostMeta($post_id, $meta_key, $meta_value)
    {
        $postMeta = $this->orderMeta->where('post_id', $post_id)->where('meta_key', $meta_key)->first();
        if(empty($postMeta))
        {
            $postMeta = $this->orderMeta->create([
                'post_id' => $post_id,
                'meta_key' => $meta_key,
                'meta_value' => $meta_value,
            ]);
        }
        else{
            $postMeta->meta_value = $meta_value;
            $postMeta->save();
        }
        return $postMeta;
    }

    protected function updateOrderLineItem(array $data)
    {
        $default_data = [
            'order_id' => 0,
            'product_id' => 0,
            'quantity' => 0
        ];
        $data = array_intersect_key($data, $default_data);
        $data = array_merge($default_data, $data);
        $data = array_map('absInt', $data);
        $item = $this->lineItem->where('order_id', $data['order_id'])->where('order_item_name', $data['product_id'])->first();
        if(empty($item))
            return;
        if($data['quantity'] <= $item->meta['origin_qty'] ?? 0)
        {
            $price = $item->meta['_price'] ?? 0;
            $price = number_format(floatval($price), 2);
            $line_total = $price * absInt($data['quantity']);
            $this->updateOrderItemMeta($item->order_item_id, '_qty', $data['quantity']);
            $this->updateOrderItemMeta($item->order_item_id, '_line_total', $line_total);
        }
    }

    public function createOrderLineItem(array $data)
    {
        $data = array_map('absInt', $data);
        $product = $this->product->find($data['product_id']) ?? $this->variation->find($data['product_id']);
        if(empty($product) || $data['quantity'] <= 0)
            return;
        $item = $this->lineItem->create(
        [
            'order_item_type' => 'line_item',
            'order_id' => $data['order_id'],
            'order_item_name' => $data['product_id'],
        ]);
        $price = $product->meta['_price'] ?? 0;
        $price = number_format(floatval($price), 2);
        $line_total = $price * $data['quantity'];
        $this->updateOrderItemMeta($item->order_item_id, '_qty', $data['quantity']);
        $this->updateOrderItemMeta($item->order_item_id, 'origin_qty', $data['quantity']);
        $this->updateOrderItemMeta($item->order_item_id, '_price', $price);
        $this->updateOrderItemMeta($item->order_item_id, '_line_total', $line_total);
    }

    protected function refundProductsToOrder($order, $products)
    {
        if(empty($products) || !is_array($products))
             return $order;
        foreach ($products as $key => $value) {
            $data['order_id'] = $order->ID;
            $data['product_id'] = $key;
            $data['quantity'] = $value;
            $this->updateOrderLineItem($data);
        }
        return $order;
    }

    public function submitOrder($order, $data)
    {
        if($order->post_status == 'wc-completed')
            return $order;
        $products = $data['products'] ?? [];
        $order = $this->refundProductsToOrder($order, $products);

        $status = $data['status'] ?? '';
        if($status == 'delivered')
        {
            $order->post_status = 'wc-on-hold';
            $order->save();
        }
        $order = $this->updateOrderTotal($order);
        return $order;
    }

    protected function updateOrderAddress($order, $data)
    {
        $data = $this->address->find(absInt($data));
        $default_data = [
            'first_name' => '',
            'last_name' => '',
            'phone_number' =>'',
            'address' => '',
            'city' =>'',
            'country' => 'Angola',
            'state' => 'Cabinda',
        ];
        foreach ($default_data as $key => $value) {
            $this->updatePostMeta($order->ID, '_shipping_'.$key, $data->$key ?? $value);
        }
        return $order;
    }

    public function createOrder($data)
    {
        $order = $this->createEmptyOrder();

        $products = $data['products'] ?? [];
        $order = $this->addProductsToOrder($order, $products);

        $address = $data['address'] ?? 0;
        $order = $this->updateOrderAddress($order, $address);

        $urgent = $data['urgent'] ?? false;
        $urgent = boolval($urgent);
        $this->updatePostMeta($order->ID, '_urgent', $urgent);

        $order = $this->updateOrderTotal($order);
        return $order;
    }

    protected function addProductsToOrder($order, $products)
    {
        if(empty($products) || !is_array($products))
            return $order;
        foreach ($products as $key => $value) {
            $data['order_id'] = $order->ID;
            $data['product_id'] = $key;
            $data['quantity'] = $value;
            $this->createOrderLineItem($data);
        }
        return $order;
    }

    protected function createEmptyOrder()
    {
        $new_order = $this->order->create([
            'post_status' => 'wc-pending',
            'post_type' => 'shop_order',
          //  'post_date' => Carbon::now(),
            ]);
        $meta_data = [
            '_customer_user' => Auth::user()->ID,
            '_order_currency' => 'USD',
            '_order_total' => 0,
        ];
        foreach ($meta_data as $key => $value) {
            $this->updatePostMeta($new_order->ID, $key, $value);
        }
        return $new_order;
    }

    protected function updateOrderTotal($order)
    {
        $total = 0;
        foreach ($order->lineItems as $item) {
           $line_total = $item->meta['_line_total'] ?? 0;
           $line_total = number_format(floatval($line_total), 2);
            $total += $line_total;
        }
        if($order->meta['_urgent']??false == true)
            $total += number_format(floatval(env('urgentShippingFee',10)),2);
        $this->updatePostMeta($order->ID, '_order_total', number_format($total, 2));
        $order->_meta['_order_total'] = $total;
        return $order;
    }
}