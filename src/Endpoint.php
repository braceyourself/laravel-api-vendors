<?php


namespace Braceyourself\ApiVendors;


use Braceyourself\ApiVendors\Support\ApiEndpoint;
use Braceyourself\ApiVendors\Support\ApiVendor;

class Endpoint extends ApiEndpoint
{
    public static function forVendor(ApiVendor $vendor, $path)
    {
        return new static($vendor, $path);
    }
}