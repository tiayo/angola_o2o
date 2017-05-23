<?php

use App\Fractal\Fractal;

if (! function_exists('fractal')) {
    /**
     * @param null|mixed $data
     * @param null|callable|\League\Fractal\TransformerAbstract $transformer
     * @param null|\League\Fractal\Serializer\SerializerAbstract $serializer
     *
     * @return \Spatie\Fractal\Fractal
     */
    function fractal($data = null, $transformer = null, $serializer = null)
    {
        return Fractal::create($data, $transformer, $serializer);
    }
}

if (! function_exists('absInt')) {
    function absInt($data)
    {
        return abs(intval($data));
    }
}

if (! function_exists('absFloat')) {
    function absFloat($data)
    {
        return number_format(abs(floatval($data)),2);
    }
}
