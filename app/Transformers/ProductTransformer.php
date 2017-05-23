<?php

namespace App\Transformers;

use App\Product;
use League\Fractal\TransformerAbstract;
use App\Transformers\ProductVariationTransformer;

class ProductTransformer extends TransformerAbstract
{

    public function transform(Product $product)
    {
        $variations = $product->variations;
        $result = [];
        $transformer = new ProductVariationTransformer();
        foreach ($variations as $value) {
            $result[] = $transformer->transform($value);
        }
        $price = $product->meta['_price'] ?? 0;
        $price = number_format($price, 2);
        return [
            'id' => (string) $product->ID,
            'name' => $product->post_title,
            'description' => $product->post_content,
            'price' => $price,
            'sku' => $product->meta['_sku'] ?? '',
            'type' => $product->cates['product_type'][0]['name'],
            'attributes' => array_values($product->meta['attributes']),
            'simple_attrs' => [
                'attr_names' => array_keys($product->meta['simple_attrs']),
                'attr_values' => $product->meta['simple_attrs']],
            'categories' => $product->cates['product_cat'] ?? [],
            'tags' => $product->cates['product_tag'] ?? [],
            'images' => $product->image,
            'variations' => $result,
        ];
    }
}



