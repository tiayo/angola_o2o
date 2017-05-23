<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\MetaTrait;

class ShippingItem extends Model
{
    use MetaTrait;

    protected $connection = 'mysql';
    protected $table = 'wp_woocommerce_order_items';
    protected $primaryKey = 'order_item_id';

    //protected $appends = ['meta'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('shipping_item', function (Builder $builder) {
            $builder->where('order_item_type', 'shipping');
        });
    }

    public function metas()
    {
        return $this->hasMany('App\ItemMeta', 'order_item_id');
    }
}