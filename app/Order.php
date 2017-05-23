<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\MetaTrait;
use App\User;

class Order extends Model
{
    use MetaTrait;

    protected $connection = 'mysql';
    protected $table = 'wp_posts';
    protected $primaryKey = 'ID';

    //public $timestamps = false;
    const CREATED_AT = 'post_date';
    const UPDATED_AT = 'post_modified';

    protected $fillable = ['post_status', 'post_type'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->where('post_type', 'shop_order')->whereNotIn('post_status' , ['wc-shoppingcart','trash']);
        });
    }

    public function scopeCustomer($query)
    {
        return $query->join('wp_postmeta', 'wp_posts.ID', '=', 'wp_postmeta.post_id')
            ->where('wp_postmeta.meta_key', '_customer_user')
            ->where('wp_postmeta.meta_value', Auth::user()->ID);
    }

    public function metas()
    {
        return  $this->hasMany('App\PostMeta', 'post_id');
    }

    public function lineItems()
    {
        return $this->hasMany('App\LineItem', 'order_id');
    }
    public function shippingItems()
    {
        return $this->hasMany('App\ShippingItem', 'order_id');
    }

    public function bindMetas()
    {
        foreach ($this->metas as $value) {
             $this->_meta[$value->meta_key] = $value->meta_value;
         }

         foreach ($this->_meta as $key => $value) {
             if(strpos($key, '_billing_') === 0)
             {
                $this->_meta['billing'][substr($key, 9)] = $value;
             }
             else if(strpos($key, '_shipping_') === 0)
            {
                $this->_meta['shipping'][substr($key, 10)] = $value;
             }
         }
        $id = $this->_meta['order_deliverer'] ?? 0;
        $user = User::find($id);
        $this->_meta['order_deliverer'] = array(
                'name' => $user->user_nicename ?? '',
                'phone' => $user->meta['phone_number'] ?? '',
        );
    }
}

