<?php


namespace Braceyourself\ApiVendor;


use Braceyourself\ApiVendor\Support\ApiEndpoint;
use Braceyourself\ApiVendor\Support\ApiVendor;

class Endpoint extends ApiEndpoint
{
    public static function forVendor(ApiVendor $vendor, $path)
    {
        return new static($vendor, $path);
    }
}