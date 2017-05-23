<?php

namespace App\Http\Controllers;

use App\Banner;
use App\Transformers\BannerTransformer;

class BannerController extends Controller
{
    public function all()
    {
        $all  = Banner::all();
        foreach ($all as $key => $value) {
           if(empty($value->image))
           {
                unset($all[$key]);
           }
        }
        return $this->transform($all, new BannerTransformer());
    }
}
