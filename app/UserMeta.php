<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'user_id', 'meta_key','meta_value',
    ];
    protected $connection = 'mysql';
    protected $table = 'wp_usermeta';
    protected $primaryKey = 'umeta_id';

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
