<?php

namespace Braceyourself\ApiVendor\Traits;

use Illuminate\Database\Eloquent\Concerns\HasAttributes;

trait HasEloquentAttributes
{
    use HasAttributes;

    public function getIncrementing()
    {
        return false;
    }

    public function usesTimestamps()
    {
        return false;
    }

    public function getDates(){
        return [];
    }
    public function relationLoaded(){
        return false;
    }

    public function __get($name)
    {
        return $this->getAttribute($name);
    }
    public function __set($name, $value)
    {
        return $this->setAttribute($name, $value);
    }
}
