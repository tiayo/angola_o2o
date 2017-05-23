<?php

namespace App\Repositories;

use App\Cart;
use App\Product;
use App\ProductVariation;
use Illuminate\Support\Facades\Auth;

class CartRepository
{
    protected $cart;
    protected $product;
    protected $variation;

    public function __construct(Cart $cart, Product $product, ProductVariation $variation)
    {
        $this->cart = $cart;
        $this->product = $product;
        $this->variation = $variation;
    }

    public function emptyCart()
    {
        $cart = $this->currentCart();
        $cart->products = '';
        $cart->save();
        return $this->decorateCart($cart);
    }

    public function getCurrentCart()
    {
        $cart = $this->currentCart();
        return $this->decorateCart($cart);
    }

    public function updateCart($data)
    {
        $cart = $this->currentCart();
        $products = unserialize($cart->products);
        if(empty($products))
            $products = [];
        if(empty($data) || !is_array($data))
        {
            $data = [];
        }
        foreach ($data as $key => $value) {
            $products[$key] = $value;
        }
        $cart->products = serialize($products);
        $cart->save();
        return $this->decorateCart($cart);
    }

    protected function currentCart()
    {
        $cart = $this->cart->where('user_id', Auth::user()->ID)->first();
        if(empty($cart))
            return $this->cart->create(['user_id' => Auth::user()->ID]);
        return $cart;
    }

    protected function decorateCart($cart)
    {
        $total = 0;
        $products = unserialize($cart->products);
        if(empty($products))
            $products = [];
        $line_item = [];
        foreach ($products as $key => $value) {
            $product = $this->product->find($key) ?? $this->variation->find($key);
            if(empty($product) || $value < 1)
                unset($products[$key]);
            else
            {
                $price = $product->meta['_price'] ?? 0;
                $price = number_format(absInt($price), 2);
                $line_item[$key]['product_id'] = (string)$key;
                $line_item[$key]['name'] = $product->title;
                $line_item[$key]['image'] = $product->avatar;
                $line_item[$key]['qty'] = (string)absInt($value);
                $line_item[$key]['price'] = $price;
                $line_total = $price * absInt($value);
                $line_item[$key]['line_total'] = number_format($line_total, 2);
                $total += $line_total;
            }
        }
        $cart->line_item =array_values($line_item);
        $cart->products = serialize($products);
        $cart->total = number_format($total, 2);
        $cart->save();
        return $cart;
    }
}