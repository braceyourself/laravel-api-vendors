<?php

namespace Braceyourself\ApiVendors\Tests\Unit;

use Braceyourself\ApiVendors\Endpoint;
use Braceyourself\ApiVendors\Tests\Stubs\VendorStub;
use Braceyourself\ApiVendors\Vendor;
use Braceyourself\ApiVendors\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

class ApiVendorTests extends TestCase
{
    /** @test */ // constructor config overrides the config file
    public function constructor_config_overrides_the_config_file(){
        $vendorName = 'CornerstonePayments';

        Config::set('api-vendors.'.$vendorName, [
            'base_url' => 'initial_url',
            'options' => [
                'auth' => []
            ]
        ]);

        $vendor = new Vendor($vendorName, [
            'base_url' => 'hello world',
        ]);

        $this->assertArrayHasKey('base_url', $vendor->config);
        $this->assertTrue($vendor->config['base_url'] == 'hello world');
        $this->assertFalse($vendor->config['base_url'] === 'initial_url');
        $this->assertArrayHasKey('options', $vendor->config);
        $this->assertArrayHasKey('auth', $vendor->config['options']);
    }


    /** @test */ // we can extend the base apiVendor class
    public function we_can_extend_the_base_api_vendor_class(){
        $endpoint = VendorStub::endpointName();

        $this->assertTrue($endpoint instanceof Endpoint);
    }

    /** @test */ // can call an endpoint from a static class
    public function can_call_an_endpoint_from_a_static_class(){

    }

    /** @test */ // make api-vendor command creates a new file
    public function make_api_model_command_creates_a_new_file(){
        Artisan::call('make:api-vendor', ['name' => 'testing']);
    }
}
