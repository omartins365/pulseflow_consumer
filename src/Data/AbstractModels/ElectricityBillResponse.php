<?php

namespace GenioForge\Consumer\Data\AbstractModels;

use Nette\NotImplementedException;

abstract class ElectricityBillResponse extends TransactionResponse
{
    public $customer_name;
    public $customer_phone;
    public $address;
    public $token_amount;
    public $token_value;

    public $tariff;
    public $units;
    public $meter_number;
    public $meter_type;
    public $token;
    /**
     * @param mixed $reference
     * @param mixed $success
     * @param mixed $message
     */
    public function __construct($reference, $success, $message, $response)
    {
        parent::__construct($reference, $success, $message, $response);
    }
    public function toLog(): array|object
    {
        return [
            ...parent::toLog(),
            'customer_name' => $this->customer_name,
            'customer_phone' => $this->customer_phone,
            'address' => $this->address,
            'token_amount' => $this->token_amount,
            'token_value' => $this->token_value,
            'tariff' => $this->tariff,
            'units' => $this->units,
            'meter_number' => $this->meter_number,
            'meter_type' => $this->meter_type,
            'token' => $this->token,
        ];
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

    public function toPinPurchaseResponse(): PinPurchaseResponse
    {
        throw new NotImplementedException();
    }
    public function toAirtimePurchaseResponse(): AirtimePurchaseResponse
    {
        throw new NotImplementedException();
    }
    public function toElectricityBillResponse(): ElectricityBillResponse
    {
        return $this;
    }
}
