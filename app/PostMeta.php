<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostMeta extends Model
{
    protected $connection = 'mysql';
    protected $table = 'wp_postmeta';
    protected $primaryKey = 'meta_id';

    protected $fillable = [
        'post_id',
        'meta_key' ,
        'meta_value'
    ];

    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function order()
    {
        return $this->belongsTo('App\Order');
    }
}
