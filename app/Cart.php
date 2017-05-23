<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $connection = 'mysql';
    protected $table = 'carts';
    protected $primaryKey = 'id';

    protected $fillable = ['user_id', 'products'];

    protected $lineItem;
    protected $total;

    public function  getLineItemAttribute()
    {
        return $this->lineItem;
    }

    public function setLineItemAttribute($value)
    {
        $this->lineItem = $value;
    }

    public function  getTotalAttribute()
    {
        return $this->total;
    }

    public function setTotalAttribute($total)
    {
        $this->total = $total;
    }
}

