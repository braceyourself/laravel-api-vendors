<?php

namespace Braceyourself\ApiVendors\Support;

use Braceyourself\ApiVendor\Contracts\ResponseCallbackContract;
use Braceyourself\ApiVendor\Http\ApiRequest;
use Braceyourself\ApiVendor\Http\ApiResponse;
use Braceyourself\ApiVendor\Traits\HasEloquentAttributes;
use Exception;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;
use Illuminate\Support\Collection;
use Zttp\PendingZttpRequest;
use Illuminate\Support\Facades\Cache;
use Zttp\ZttpResponse;


abstract class ApiEndpoint
{
    use HasEloquentAttributes;

    private $responseCallbacks = [];

    protected $headers = [];
    protected $options = [];
    protected $params = [];
    protected $body_format = 'json';
    protected $method;
    protected $responseRules;
    protected $base_url;
    /**
     * @var ApiVendor
     */
    private $vendor;
    /**
     * @var array
     */
    private $config;


    /**
     * ApiEndpoint constructor.
     * @param ApiVendor $vendor
     * @param $path
     */
    public function __construct(ApiVendor $vendor, $path)
    {
        $this->vendor = $vendor;

        $this->setAttribute('path', $path);

    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getBodyFormat()
    {
        return $this->body_format;
    }

    public function getRequestOptions() : array
    {
        return $this->options['request'];
    }

    /**
     * @param array $appendPath
     * @return string
     */
    public function buildUri(...$appendPath)
    {
        $base_url = rtrim($this->vendor->base_url, '/');
        $endpoint_uri = trim($this->path, '/');

        foreach($appendPath as $path){
            $path = trim($path, '/');
            $endpoint_uri .= "/$path";
        }

        return tap(trim("$base_url/$endpoint_uri", '/'), function ($uri){
            foreach ($this->params as $key => $value) {
                if ($key === array_key_first($this->params))
                    $uri .= '?';

                $uri .= "$key=$value";

                if ($key !== array_key_first($this->params))
                    $uri .= '&';

            }
            return $uri;
        });
    }

    /**
     * @return string    private function request($method = 'GET', $data = [])
     */
    private function getCacheKey()
    {
        $key = $this->method . "-" . $this->buildUri();

        if (!empty($this->options)) {
            $value = $this->options;
            if (is_array($value)) {
                $value = http_build_query($value, null, '&', PHP_QUERY_RFC3986);
            }
            if (is_string($value)) {
                $key .= "-" . $value;
            }
        }

        return $key;
    }

    /**
     * @param string $method
     * @param null $id
     * @param array $data
     * @return ApiResponse
     */

    /**
     * @param ResponseCallbackContract $collectionCallback
     */
    private function registerCollectionCallback(ResponseCallbackContract $collectionCallback)
    {
        $this->responseCallbacks[] = $collectionCallback;
    }


    /**
     * @param $id
     * @param string $id_name
     * @return ApiResponse
     */
    final public function find($id, $id_name = 'id')
    {
        $response = ApiRequest::to(
            $this,
            'get'
        )->get();

        return $response;
    }

    /**
     * @param array $data
     * @return ApiResponse
     */
    final public function get($data = [])
    {
        return ApiRequest::to($this)->get();

    }

    /**
     * @param array $data
     * @return ApiResponse
     * @throws Exception
     */
    final public function post(array $data)
    {
        return ApiRequest::to($this)->withPostData($data)->post();
    }


    /**
     * @param $name
     * @param $arguments
     * @return $this
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        $collectionCallback = "\App\CollectionCallbacks\\" . ucfirst($name) . "CollectionCallback";

        if (!class_exists($collectionCallback)) {
            $collectionCallback = "\Braceyourself\ApiVendor\CollectionCallbacks\\" . ucfirst($name) . "CollectionCallback";
        }

        if (!class_exists($collectionCallback)) {
            $this->registerCollectionCallback(
                (new _ReflectionCollectionCallback(... $arguments))->setMethod($name)
            );
            return $this;
        }

        $this->registerCollectionCallback(new $collectionCallback(... $arguments));
        return $this;
    }

    /**
     * @param ZttpResponse $response
     * @return Collection|ZttpResponse
     * @throws Exception
     */
    public function applyCallbacks(ZttpResponse $response)
    {
        /** @var ResponseCallbackContract $callback */
        foreach ($this->responseCallbacks as $callback) {
            $response = $callback->applyTo($response);
        }

        return $response;
    }



    public static function forVendor(ApiVendor $vendor, $path)
    {
        return new static($vendor, $path);
    }
}
