<?php

namespace GenioForge\Consumer\Data\AbstractModels;

use Nette\NotImplementedException;

abstract class TransactionResponse extends ApiResponse
{
    public $reference;

    public $client_reference;
    public $status;
    public $success;
    public $message;

    public $amount_charged;
    public $fee;
    public $true_response = '';
    /**
     * @param mixed $reference
     * @param mixed $success
     * @param mixed $message
     */
    public function __construct($reference, $success, $message, $response)
    {
        $this->reference = $reference;
        $this->success = $success;
        $this->message = $message;

        parent::__construct($response);
    }

    abstract public function toPinPurchaseResponse():PinPurchaseResponse;
    abstract public function toAirtimePurchaseResponse():AirtimePurchaseResponse;
    abstract public function toElectricityBillResponse():ElectricityBillResponse;

    public function network_response(): string
    {
        return empty($this->true_response) ? $this->message??'' : $this->true_response??'';
    }
    public function toLog(): array|object
    {
        return [
            ...parent::toLog(),
            'reference' => $this->reference,
            'client_reference' => $this->client_reference,
            'status' => $this->status,
            'success' => $this->was_successful(),
            'failed' => $this->has_failed(),
            'refunded' => $this->was_refunded(),
            'pending' => $this->is_pending(),
            'message' => $this->message,
            'amount_charged' => $this->amount_charged??$this->amount??null,
            'fee' => $this->fee,
            'true_response' => $this->true_response,
        ];
    }
    public function safeLog(): array|object
    {
        return [
            ...parent::safeLog(),
            'status' => $this->status,
            'success' => $this->was_successful(),
            'message' => $this->network_response(),
        ];
    }
}
