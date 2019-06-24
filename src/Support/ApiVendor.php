<?php

namespace Braceyourself\ApiVendor\Support;

use Braceyourself\ApiVendor\Endpoint;
use Braceyourself\ApiVendor\Http\ApiRequest;
use Braceyourself\ApiVendor\Traits\HasEloquentAttributes;
use Illuminate\Support\Facades\Config;

/**
 * @property array config
 */
abstract class ApiVendor
{
    use HasEloquentAttributes;

    public function __construct(string $name, array $config = [])
    {

        $this->setAttribute('name', $name);

        foreach ($config as $key => $value) {
            $this->setAttribute($key, $value);
        }

    }

    public static function withBase(string $name, string $base_url)
    {
        return new static($name, ['base_url'=>$base_url]);
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {

//        return an attribute if it exists
        if ($attribute = $this->getAttribute($key)) {
            return $attribute;
        }
        // otherwise create a dynamic endpoint
        return Endpoint::forVendor($this, $key);

    }


    protected function getName()
    {
        return class_basename($this);
    }

    /**
     * @param $endpointName
     * @param $arguments
     * @return ApiEndpoint
     * @throws \Exception
     */
    public static function __callStatic($endpointName, $arguments)
    {
        $calledClass = get_called_class();
        dd(new $calledClass(self::class));
        return Endpoint::forVendor($calledClass, $endpointName);

    }


    /**
     * @param $name
     * @return string
     * @throws \ReflectionException
     */
    private static function resolveEndpointClass($name)
    {
    }


    /**
     * @param mixed ...$append
     * @return string
     * @throws \ReflectionException
     */
    private static function buildFullyQualifiedName(...$append)
    {
        $namespaceName = (new \ReflectionClass(get_called_class()))->getNamespaceName();

        foreach ($append as $arg) {
            $namespaceName .= "\\$arg";
        }

        return $namespaceName;
    }


    public function configKey()
    {
        return sprintf('api-vendors.%s', $this->name ?? $this->getName());
    }
}
