<?php

namespace App;

trait MetaTrait
{

    public $_meta = array();

    public function getMetaAttribute()
    {
        if(empty($this->_meta))
        {
            $this->bindMetas();
        }
        return $this->_meta;
    }

    public function bindMetas()
    {
        foreach ($this->metas as $value) {
             $this->_meta[$value->meta_key] = $value->meta_value;
         }
    }

}