<?php


namespace Braceyourself\ApiVendors\Http;


use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Traits\Macroable;
use Zttp\ZttpResponse;

class ApiResponse
{
    use Macroable;
    protected $data;
    public $errors;
    private $response;

    public function __construct(ZttpResponse $response)
    {
        $this->response = $response;
    }

    /**
     * @param array $merge
     * @return \Illuminate\Support\Collection|\Tightenco\Collect\Support\Collection
     */
    public function all(array $merge = [])
    {
        return collect(array_merge([
            'data' => $this->response->json()
        ], $merge));
    }

    public function addErrors(array $errors)
    {
        $this->errors = $errors;
    }

    public function withErrors(){
        return $this->all()->merge([
            'errors' => $this->errors
        ]);
    }

    public function validate(array $rules, array $messages = [], array $customAttributes = []){
        Validator::make(
            $this->all()->toArray(),
            $rules,
            $messages,
            $customAttributes
        )->validate();
    }

//    private function validate($method, ApiResponse $response)
//    {
//        try {
//            if (isset($this->validation[$method])) {
//                $response->validate(
//                    $this->validation[$method]['rules'] ?? [],
//                    $this->validation[$method]['messages'] ?? [],
//                    $this->validation[$method]['customAttributes'] ?? []
//                );
//            }
//        } catch (ValidationException $e) {
//            $response->addErrors($e->errors());
//        }
//
//
//    }
}
