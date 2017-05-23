<?php

namespace App\Transformers;

use App\Banner;
use League\Fractal\TransformerAbstract;
use App\Transformers\LineItemTransformer;

class BannerTransformer extends TransformerAbstract
{

    public function transform(Banner $banner)
    {
        return [
            'image' => $banner->image,
            'redirect' => $banner->post_content,
        ];
    }
}



