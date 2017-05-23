<?php

namespace App\Http\Controllers;

use App\Transformers\CartTransformer;
use App\Repositories\CartRepository;
use Illuminate\Http\Request;

class CartController extends Controller
{

    protected $cart;

    public function __construct(CartRepository $cart)
    {
        $this->cart = $cart;
    }

    public function current()
    {
        return $this->transform($this->cart->getCurrentCart(), new CartTransformer());
    }

    public function updateCurrent(Request $request)
    {
        $data = $request->all();
        return $this->transform($this->cart->updateCart($data), new CartTransformer());
    }

    public function clear()
    {
        return $this->transform($this->cart->emptyCart(), new CartTransformer());
    }
}
