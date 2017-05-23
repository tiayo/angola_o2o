<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\MetaTrait;
use Illuminate\Support\Facades\DB;

class LineItem extends Model
{
    use MetaTrait;

    protected $connection = 'mysql';
    protected $table = 'wp_woocommerce_order_items';
    protected $primaryKey = 'order_item_id';

    protected $fillable = [
        'order_item_type',
        'order_id' ,
        'order_item_name'
    ];

    public $timestamps = false;
    //protected $appends = ['meta'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('line_item', function (Builder $builder) {
            $builder->where('order_item_type', 'line_item');
        });
    }

    public function metas()
    {
        return $this->hasMany('App\ItemMeta', 'order_item_id');
    }

    public function getNameAttribute()
    {
        if(!empty($this->product))
            return $this->product->post_title;
        if(!empty($this->variation))
            return $this->variation->rootProduct->post_title;
        return '';
    }

    public function getAvatarAttribute()
    {
        $product = $this->product ?? $this->variation;
        return $product->avatar ?? '';
    }

    public function product()
    {
        return $this->belongsTo('App\Product', 'order_item_name');
    }
    public function variation()
    {
        return $this->belongsTo('App\ProductVariation', 'order_item_name');
    }
}