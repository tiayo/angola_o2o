<?php

namespace App\Http\Controllers;

use App\Services\AddressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Transformers\AddressTransformer;

class AddressController extends Controller
{

    protected $address;
    protected $request;
    protected $user_id;

    public function __construct(AddressService $address, Request $request)
    {
        $this->address = $address;
        $this->request = $request;
        $this->user_id = Auth::user()->ID;
    }

    /*
     * get all user address,'get'
     * */
    public function index()
    {
        $address = $this->address->find($this->user_id);
        return $this->transform($address, app(AddressTransformer::class));
    }

    /*
     * Update user address,'put'
     * */
    public function update($id)
    {
        $data = $this->request->all();
        unset($data['key']);//Remove key
        try{
            $this->address->update($id, $this->user_id, $data);
        }catch (\Exception $e) {
            return $this->error('update error');
        }

        return $this->success();
    }

    /*
     * insert user address,'post'
     * */
    public function store()
    {
        $data = $this->request->all();
        try {
            $this->address->insert($this->user_id, $data);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

        return $this->success();
    }

    /*
     * delete user address,'del'
     * */
    public function delete($id)
    {
        try {
            $this->address->delete($id);
        } catch (\Exception $e) {
            return $this->error('delete error');
        }

        return $this->success();
    }
}