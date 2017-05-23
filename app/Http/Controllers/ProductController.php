<?php

namespace App\Http\Controllers;

use App\Product;
use App\Transformers\ProductTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function all(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'per_page' => 'required | integer | min:1',
        ]);
        if ($validator->fails()) {
             $request['per_page'] = 3;
        }
         return $this->transform($this->product->paginate($request['per_page']), new ProductTransformer());
    }

    public function one($id)
    {
        $product = $this->product->find($id);
        if(empty($product))
            return response(['code' => 'product_not_found', 'message' => 'cant find product by id '.$id, 'data' => ['status' => 404]],404);
        return $this->transform($product, new ProductTransformer());
    }
}
