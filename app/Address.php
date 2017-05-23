<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'address','is_default','user_id','first_name','last_name','phone_number','city','country','state'
    ];
    protected $connection = 'mysql';
    protected $table = 'wp_addresses';
    protected $primaryKey = 'id';

}

