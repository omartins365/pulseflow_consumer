<?php

namespace GenioForge\Consumer\Data\AbstractModels;

use Nette\NotImplementedException;

abstract class PinPurchaseResponse extends TransactionResponse
{
    public $pins;
    public $quantity;
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
            'pins' => $this->pins,
            'quantity' => $this->quantity??(is_countable($this->pins)?count($this->pins):null),
        ];
    }
    public function safeLog(): array|object
    {
        return [
            ...parent::safeLog(),
            'pins' => $this->pins,
            'quantity' => $this->quantity??(is_countable($this->pins)?count($this->pins):null),
        ];
    }

    public function toPinPurchaseResponse(): PinPurchaseResponse
    {
        return $this;
    }
    public function toAirtimePurchaseResponse(): AirtimePurchaseResponse
    {
        throw new NotImplementedException();
    }
    public function toElectricityBillResponse(): ElectricityBillResponse
    {
        throw new NotImplementedException();
    }
}
