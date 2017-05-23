<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\MetaTrait;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use MetaTrait;

    protected $connection = 'mysql';
    protected $table = 'wp_posts';
    protected $primaryKey = 'ID';
    //protected $appends = array('images');

    protected $_cats = [];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('product', function (Builder $builder) {
            $builder->where('post_type', 'product')->where('post_status', 'publish');
        });
    }

    public function metas()
    {
        return  $this->hasMany('App\PostMeta', 'post_id');
    }

    public function getVariationAttribute()
    {
        if($this->cates['product_type'][0]['name'] == 'variable')
        {
            return $this->variations;
        }
        return [];
    }

    public function variations()
    {
        return $this->hasMany('App\ProductVariation', 'post_parent');
    }

    public function getVariationById($id)
    {
        foreach ($this->variation as  $value) {
            if($value->ID == $id)
                return $value;
        }
        return null;
    }

    public function bindMetas()
    {
        foreach ($this->metas as $value) {
             $this->_meta[$value->meta_key] = $value->meta_value;
         }
         $result = $this->_meta['_product_attributes'] ?? '';
         $this->_meta['attributes'] = [];
         $this->_meta['simple_attrs'] = [];
         if(!empty($result))
         {
            $result = unserialize($result);
            foreach ($result as $key) {
                if($key['is_variation'] == TRUE){
                    $value = $key['value'];
                    unset($key['value']);
                    unset($key['is_variation']);
                    unset($key['is_visible']);
                    unset($key['is_taxonomy']);
                    $key['options'] = array_map('trim', explode('|', $value));
                    $this->_meta['attributes'][$key['name']] = $key;
                }
                else{
                    $this->_meta['simple_attrs'][$key['name']] = $key['value'];
                }
            }
         }
    }

    public function getImageAttribute()
    {
        $images = [];

        if (!empty($this->meta['_thumbnail_id']) ) {
             $images[] = $this->meta['_thumbnail_id'];
        }

        $add_imgs = explode(',', $this->meta['_product_image_gallery']);
        $images_total = array_merge($images, $add_imgs);

        $result = array();
        if(!empty($images_total)){
            foreach ($images_total as $key => $value) {
                $result[] = array('id'=>$value, 'position'=>(string)$key, 'src' => DB::table('wp_posts')->find($value)->guid ?? '');
            }
         }
         return $result;
    }

    public function getAvatarAttribute()
    {
        return $this->image[0]['src'] ?? '';
    }

    public function getTitleAttribute()
    {
        return $this->post_title;
    }

    public function getCatesAttribute()
    {
        if(empty($this->_cats))
            $this->bindCates();
        return $this->_cats;
    }

    public function bindCates()
    {
        $result =  DB::table('wp_posts')
            ->join('wp_term_relationships', 'wp_posts.ID', '=', 'wp_term_relationships.object_id')
            ->join('wp_term_taxonomy', 'wp_term_relationships.term_taxonomy_id', '=', 'wp_term_taxonomy.term_taxonomy_id')
            ->join('wp_terms', 'wp_term_taxonomy.term_id', '=', 'wp_terms.term_id')
            ->where('wp_posts.ID', $this->ID)
            ->select('wp_term_taxonomy.taxonomy', 'wp_terms.term_id', 'wp_terms.name')
            ->get();
        foreach ($result as $value) {
            $this->_cats[$value->taxonomy][] = array('id' => $value->term_id, 'name' => $value->name);
        }
    }
}

