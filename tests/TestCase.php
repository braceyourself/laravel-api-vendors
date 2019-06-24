<?php namespace Braceyourself\ApiVendors\Tests;

use Braceyourself\ApiVendors\ApiVendorServiceProvider;
use Braceyourself\ApiVendors\Vendor;

class TestCase extends \Orchestra\Testbench\TestCase
{

    protected function getPackageProviders($app)
    {
        return [ApiVendorServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
//            'ApiVendor' => ApiCon
        ];
    }
}
