<?php


namespace Braceyourself\ApiVendors;


use Braceyourself\ApiVendors\Support\ApiVendor;
use Illuminate\Database\Eloquent\Concerns\HasAttributes;

class Vendor extends ApiVendor {

    public function __construct(string $name, array $config = [])
    {
        parent::__construct($name, $config);
    }

}