<?php


namespace Braceyourself\ApiVendor;


use Braceyourself\ApiVendor\Support\ApiVendor;
use Illuminate\Database\Eloquent\Concerns\HasAttributes;

class Vendor extends ApiVendor {

    public function __construct(string $name, array $config = [])
    {
        parent::__construct($name, $config);
    }

}