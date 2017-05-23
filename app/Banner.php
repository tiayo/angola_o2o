<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\MetaTrait;
use Illuminate\Support\Facades\DB;

class Banner extends Model
{
    use MetaTrait;

    protected $connection = 'mysql';
    protected $table = 'wp_posts';
    protected $primaryKey = 'ID';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('product', function (Builder $builder) {
            $builder->where('post_type', 'banner');
        });
    }

    public function metas()
    {
        return  $this->hasMany('App\PostMeta', 'post_id');
    }

    public function getImageAttribute()
    {
        $id =  $this->meta['_thumbnail_id'] ?? 0;
        return DB::table('wp_posts')->find($id)->guid ?? '';
    }
}

