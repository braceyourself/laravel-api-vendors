<?php


namespace Braceyourself\ApiVendor\Http;


use Braceyourself\ApiVendor\Contracts\ApiRequestContract;
use Braceyourself\ApiVendor\Support\ApiEndpoint;
use GuzzleHttp\Psr7\Response;
use Illuminate\Validation\ValidationException;
use Zttp\PendingZttpRequest;
use Zttp\Zttp;
use Zttp\ZttpRequest;
use Zttp\ZttpResponse;


/**
 * @method ApiResponse get()
 * @method ApiResponse post()
 * @method ApiResponse put()
 * @method ApiResponse patch()
 * @method ApiResponse delete()
 * Class ApiRequest
 * @package Braceyourself\ApiVendor\Http
 */
class ApiRequest
{
    protected $validation = [];
    protected $validate_response = false;

    private $options;
    private $uri;
    /**
     * @var PendingZttpRequest
     */
    private $client;
    /**
     * @var ApiEndpoint
     */
    private $endpoint;
    private $method;
    private $params;

    public function __construct(ApiEndpoint $endpoint)
    {
        $this->client = new PendingZttpRequest();
        $this->endpoint = $endpoint;
    }

    public static function to(ApiEndpoint $endpoint): ApiRequest
    {
        return new static($endpoint);
    }


    private function prepareRequest()
    {
        $this->setHeaders()
            ->setBodyFormat()
            ->buildAuthentication();
    }

    private function buildAuthentication()
    {
        $auth = $this->options['auth'];

        if (!empty($authBasic = $auth['basic'])) {

            $this->client = $this->client->withBasicAuth(
                $authBasic['username'] ?? $authBasic[0],
                $authBasic['password'] ?? $authBasic[1]
            );

        } else if (isset($auth['key'])) {

            $this->params['key'] = $auth['key'];
        }

        return $this;
    }

    private function setBodyFormat()
    {
        $this->client->bodyFormat = $this->endpoint->getBodyFormat();

        return $this;
    }

    private function setHeaders()
    {

        $this->client->withHeaders($this->endpoint->getHeaders());

        return $this;

    }

    public function __call($method, $arguments)
    {
        $this->prepareRequest();

        return new ApiResponse(
            $this->makeRequest($method)
        );
    }

    private function makeRequest($method)
    {
        $uri = $this->endpoint->buildUri();
        $params = $this->params;
        return $this->client->$method($uri, $params);
    }

    public function withPostData(array $data)
    {
        return tap($this, function(ApiRequest $request)use($data){
            $this->params = $data;
        });

    }

}