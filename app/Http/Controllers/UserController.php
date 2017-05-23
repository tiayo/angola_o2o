<?php

namespace App\Http\Controllers;

use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\CustomerService;


class UserController extends Controller
{

    protected $customer;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CustomerService $customer)
    {
        $this->customer = $customer;
    }

    public function current()
    {
        return $this->transform($this->customer->current(Auth::user()->ID), new UserTransformer());
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'photo' => 'file|image|max:2048'
        ]);

        $data = $request->all();

        try{
            $this->customer->core($data);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
        return $this->transform($this->customer->current(Auth::user()->ID), new UserTransformer());
    }
}
