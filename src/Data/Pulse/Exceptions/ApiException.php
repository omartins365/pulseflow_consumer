<?php
namespace GenioForge\Consumer\Data\Pulse\Exceptions;

use Exception;
use GenioForge\Consumer\Data\Pulse\Models\NetworkResponse;

class ApiException extends Exception
{
    use NetworkResponse;

    protected $apiResponse;

    public function __construct($apiResponse)
    {
        $this->apiResponse = $apiResponse;
        $this->message = $this->getApiMessage();
        $this->code = $this->getApiCode();
    }

    public function getApiMessage(): string
    {
        return $this->apiResponse->message;
    }

    public function getApiCode()
    {
        return $this->apiResponse->code;
    }

    public function getError()
    {
        return $this->apiResponse->error;
    }

    public static function fromResponse($apiResponse)
    {
        switch ($apiResponse->code) {
            case 401:
                return new UnauthenticatedApiException($apiResponse);
            case 403:
                return new MissingAbilityApiException($apiResponse);
            case 404:
                return new ResourceNotFoundApiException($apiResponse);
            case 405:
                return new MethodNotAllowedApiException($apiResponse);
            case 422:
                return new ApiValidationException($apiResponse);
            default:
                return new ApiRequestFailure($apiResponse);
        }
    }

    public function __toString()
    {
        return get_class($this) . " [{$this->apiResponse->code}] : {$this->apiResponse->message} \n {$this->apiResponse->error} ";
    }

    public function errorFor($field)
    {
        return isset($this->apiResponse->error[$field]) ? $this->apiResponse->error[$field][0] : null;
    }
}



