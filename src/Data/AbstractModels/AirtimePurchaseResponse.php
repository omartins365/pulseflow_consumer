<?php

namespace GenioForge\Consumer\Data\AbstractModels;

use Nette\NotImplementedException;

abstract class AirtimePurchaseResponse extends TransactionResponse
{
    public $mobile_no;
    public $airtime_amount;
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
            'mobile_no' => $this->mobile_no,
            'airtime_amount' => $this->airtime_amount,
        ];
    }
    public function safeLog(): array|object
    {
        return [
            ...parent::safeLog(),
            'mobile_no' => $this->mobile_no,
            'airtime_amount' => $this->airtime_amount,
        ];
    }

    public function toPinPurchaseResponse(): PinPurchaseResponse
    {
        throw new NotImplementedException();
    }
    public function toAirtimePurchaseResponse(): AirtimePurchaseResponse
    {
        return $this;
    }
    public function toElectricityBillResponse(): ElectricityBillResponse
    {
        throw new NotImplementedException();
    }
}
