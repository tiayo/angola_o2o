<?php

namespace App\Transformers;

use App\ProductVariation;
use League\Fractal\TransformerAbstract;

class ProductVariationTransformer extends TransformerAbstract
{

    public function transform(ProductVariation $product)
    {

        $price = $product->meta['_price'] ?? 0;
        $price = number_format($price, 2);
        return [
            'id' => (string) $product->ID,
            'price' => $price,
            'sku' => $product->meta['_sku'] ?? '',
            'images' => $product->image,
            'attributes' => $product->meta['attributes'],
        ];
    }
}



