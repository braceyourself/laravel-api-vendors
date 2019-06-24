<?php

namespace Braceyourself\ApiVendor\Tests\Unit;

use Braceyourself\ApiVendor\Endpoint;
use Braceyourself\ApiVendor\Http\ApiResponse;
use Braceyourself\ApiVendor\Tests\Stubs\VendorStub;
use Braceyourself\ApiVendor\Vendor;
use Braceyourself\ApiVendor\Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tightenco\Collect\Support\Collection;

class ApiEndpointTests extends TestCase
{
    /** @test */ // calling an endpoint sends a request
    public function calling_an_endpoint_sends_a_request(){
        $vendor = new Vendor('vendorname',[
            'base_url' => 'https://google.com'
        ]);

        /** @var Endpoint $transactions */
        $transactions = $vendor->transactions;
        $transactions->find(1);

        $this->assertTrue($transactions instanceof Collection, 'vendor static call did not return an endpint');

    }

    /** @test */ // get will return an ApiResponse
    public function get_will_return_an_api_response(){
        $endpoint = new Endpoint(Vendor::withBase('testvendor','http://hello.world'),'/test');
        $response = $endpoint->get();
        $this->assertTrue($response instanceof ApiResponse);
    }

    /** @test */ // post will send a post request
    public function post_will_send_a_post_request(){
        $endpoint = new Endpoint(Vendor::withBase('testing','http://hello.world'), '/test');
        $response = $endpoint->post([]);
        $this->assertTrue($response instanceof ApiResponse);
    }


    /** @test */ // forVendor will give a new endpoint for a vendor
    public function for_vendor_will_give_a_new_endpoint_for_a_vendor(){
        $endpoint = Endpoint::forVendor(new Vendor('avendor'), '/boom');

        $this->assertTrue($endpoint instanceof Endpoint);
    }
}
