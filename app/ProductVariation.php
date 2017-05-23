<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\MetaTrait;
use Illuminate\Support\Facades\DB;

class ProductVariation extends Model
{
    use MetaTrait;

    protected $connection = 'mysql';
    protected $table = 'wp_posts';
    protected $primaryKey = 'ID';


    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('product', function (Builder $builder) {
            $builder->where('post_type', 'product_variation');
        });
    }

    public function metas()
    {
        return  $this->hasMany('App\PostMeta', 'post_id');
    }

    public function bindMetas()
    {
        foreach ($this->metas as $value) {
                $this->_meta[$value->meta_key] = $value->meta_value;
        }
        $this->_meta['attributes'] = [];
        foreach ($this->_meta as  $key => $value) {
            if(strpos($key, 'attribute_') === 0)
            {
                $this->_meta['attributes'][$this->rootProduct->meta['attributes'][substr($key, 10)]['position']]= $value;
            }
        }
        ksort( $this->_meta['attributes']);
        $this->_meta['attributes'] = implode(',',  $this->_meta['attributes']);
    }

    public function rootProduct()
    {
        return $this->belongsTo('App\Product', 'post_parent');
    }

    public function getImageAttribute()
    {
        $images = 0;
        if (!empty($this->meta['_thumbnail_id']) ) {
             $images = $this->meta['_thumbnail_id'];
        }
        return DB::table('wp_posts')->find($images)->guid ?? '';
    }

    public function getAvatarAttribute()
    {
        if(empty($this->image))
            return $this->rootProduct->avatar;
        return $this->image;
    }

    public function getTitleAttribute()
    {
        return $this->rootProduct->title;
    }
}

