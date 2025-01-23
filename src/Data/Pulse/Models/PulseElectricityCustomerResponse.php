<?php

namespace GenioForge\Consumer\Data\Pulse\Models;


use GenioForge\Consumer\Data\AbstractModels\ElectricityBillResponse;
use GenioForge\Consumer\Data\AbstractModels\TransactionResponse;
use GenioForge\Consumer\Data\Utils\Enums\TransactionStatus;

class PulseElectricityCustomerResponse extends \GenioForge\Consumer\Data\AbstractModels\ApiResponse
{
    public function __construct($response)
    {
        parent::__construct($response);


        $this->meter_number = $response['meter_number']??null;
        $this->customer_name = $response['name']??null;
        $this->customer_phone = $response['phone']??null;
        $this->address = $response['address']??null;
        $this->token_amount = $response['token_amount']??null;
        $this->token_value = $response['token_value']??null;
        $this->tariff = $response['tariff']??null;
        $this->units = $response['units']??null;
        $this->meter_type = $response['meter_type']??null;
        $this->token = $response['token']??null;
    }

    public function safeLog(): array|object
    {
        return [
            ...parent::safeLog(),
            'success' => $this->was_successful(),
            ...($this->was_successful() ? [
                'name' => trim($this->customer_name),
                'phone' => $this->customer_phone,
                'address' => $this->address,
                'token_amount' => $this->token_amount,
                'token_value' => $this->token_value,
                'tariff' => $this->tariff,
                'units' => $this->units,
                'meter_number' => $this->meter_number,
                'meter_type' => $this->meter_type,
                'token' => $this->token,
            ] : []),
        ];
    }
}
