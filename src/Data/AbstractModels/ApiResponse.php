<?php

namespace GenioForge\Consumer\Data\AbstractModels;

use Nette\NotImplementedException;
use GenioForge\Consumer\Exceptions\BadResponseException;

abstract class ApiResponse
{
    public $response;

    /**
     * @param mixed $reference
     * @param mixed $success
     * @param mixed $message
     */
    public function __construct($response)
    {
        if (is_array($response)) {
            $response = (object) $response;
        } else if (is_string($response)) {
            $response = json_decode($response);
            if (json_last_error()) {
                throw new BadResponseException();
            }
        } else if (!is_object($response)) {
            throw new BadResponseException();
        }
        $this->response = $response;
    }
    /**
     * @return string
     */
    public function __toString(): string
    {
        return json_encode($this->response);
    }

    public function toLog(): array|object
    {
        return [
            'response' => $this->response,
        ];
    }
    public function safeLog(): array|object
    {
        return [
            /* 'response' => $this->response, */];
    }
    public function was_successful(): ?bool
    {
        return null;
    }

    public function was_refunded(): ?bool
    {
        return null;
    }
    public function is_pending(): ?bool
    {
        return null;
    }

    public function has_failed(): ?bool
    {
        return null;
    }
    public function network_response(): string
    {
        return '';
    }
}
