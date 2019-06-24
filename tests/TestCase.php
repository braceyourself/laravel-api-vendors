<?php namespace Braceyourself\ApiVendor\Tests;

use Braceyourself\ApiVendor\ApiVendorServiceProvider;
use Braceyourself\ApiVendor\Vendor;

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
