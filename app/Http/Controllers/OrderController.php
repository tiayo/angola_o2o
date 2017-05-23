<?php

namespace App\Http\Controllers;

use App\Order;
use App\Transformers\OrderTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Repositories\OrderRepository;

class OrderController extends Controller
{

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function all(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'per_page' => 'required | integer | min:1',
        ]);
        if ($validator->fails()) {
             $request['per_page'] = 3;
        }
        return $this->transform($this->order->customer()->paginate($request['per_page']), new OrderTransformer());
    }

    public function one($id)
    {
        $order = $this->order->customer()->find($id);
        if(empty($order))
        {
            return response(['code' => 'order_not_found', 'message' => 'cant find order by id '.$id, 'data' => ['status' => 404]],404);
        }
        return $this->transform($order, new OrderTransformer());
    }

    public function updateOne(OrderRepository $updater, Request $request, $id)
    {
        $data = $request->all();
        $order = $this->order->customer()->find($id);
        if(empty($order))
        {
            return response(['code' => 'order_not_found', 'message' => 'cant find order by id '.$id, 'data' => ['status' => 404]],404);
        }
        return $this->transform($updater->submitOrder($order, $data), new OrderTransformer());
    }

    public function create(OrderRepository $updater, Request $request)
    {
        $data = $request->all();
        return $this->transform($updater->createOrder($data), new OrderTransformer());
    }
}
