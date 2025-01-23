<?php

namespace GenioForge\Consumer\Data\Pulse\Models;

use GenioForge\Consumer\Data\AbstractModels\AirtimePurchaseResponse;
use GenioForge\Consumer\Data\AbstractModels\ElectricityBillResponse;
use GenioForge\Consumer\Data\AbstractModels\PinPurchaseResponse;
use GenioForge\Consumer\Data\AbstractModels\TransactionResponse;
use GenioForge\Consumer\Data\Utils\Enums\TransactionStatus;
use Nette\NotImplementedException;

class PulseTransactionResponse extends TransactionResponse
{
use PulseStatus, NetworkResponse;
    public function __construct(public Transaction $transaction)
    {
        parent::__construct($transaction->ref,
            $transaction->status == TransactionStatus::SUCCESS, $transaction->message, $transaction->toJson());
        $this->amount_charged = $this->transaction->amountPaid;
        $this->fee = $this->transaction->amountPaid - $this->transaction->amount;
        $this->client_reference = $this->transaction->clientRef;
    }

    public function toPinPurchaseResponse(): PinPurchaseResponse
    {
        // TODO: Implement toPinPurchaseResponse() method.
        throw NotImplementedException();
    }

    public function toAirtimePurchaseResponse(): AirtimePurchaseResponse
    {
        // TODO: Implement toAirtimePurchaseResponse() method.
        throw NotImplementedException();
    }

    public function toElectricityBillResponse(): ElectricityBillResponse
    {
        // TODO: Implement toElectricityBillResponse() method.
        throw NotImplementedException();
    }


}
