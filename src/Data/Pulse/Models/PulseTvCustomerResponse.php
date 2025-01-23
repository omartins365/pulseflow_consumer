<?php

namespace GenioForge\Consumer\Data\Pulse\Models;


use GenioForge\Consumer\Data\AbstractModels\ElectricityBillResponse;
use GenioForge\Consumer\Data\AbstractModels\TransactionResponse;
use GenioForge\Consumer\Data\Utils\Enums\TransactionStatus;

class PulseTvCustomerResponse extends \GenioForge\Consumer\Data\AbstractModels\ApiResponse
{
use NetworkResponse;
    public $success;
    public $message_code;
    public $customer_name;
    public $subscription_status;
    public $due_date;
    public $customer_type;

    public $current_plan;

    public function __construct($response)
    {
        parent::__construct('',
            !empty($response), '', $response);


        $this->customer_name = $response['name']??null;
        $this->subscription_status = $response['subscription_status']??null;
        $this->due_date = $response['due_date']??null;
        $this->service_provider = $response['service_provider']??null;
        $this->current_plan = $response['current_plan']??null;
    }

    public function was_successful(): bool
    {
        return !empty($this->customer_name) ;
    }

    public function has_failed(): bool
    {
        return strtolower($this->success) == "false" || strtolower($this->success) == "false_disabled";
    }

    public function safeLog(): array|object
    {
        return [
            ...parent::safeLog(),
            'success' => $this->was_successful(),
            ...($this->was_successful()?[
                "name" => $this->customer_name,
                "subscription_status" => $this->subscription_status,
                "due_date" => $this->due_date,
                "service_provider" => $this->customer_type,
                "current_plan" => str($this->current_plan)->headline(),
            ]:[]),
        ];
    }
}
