<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

use App\MetaTrait;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, MetaTrait;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_login', 'user_pass','user_nicename','user_email','user_url','user_registered','display_name','user_registered',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    protected $connection = 'mysql';
    protected $table = 'wp_users';
    protected $primaryKey = 'ID';

    public function metas()
    {
        return  $this->hasMany('App\UserMeta');
    }

    public function address()
    {
        return  $this->hasMany('App\Address');
    }

}
