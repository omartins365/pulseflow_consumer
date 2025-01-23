<?php

namespace GenioForge\Consumer\Data\Pulse\Models;

use GenioForge\Consumer\Data\AbstractModels\AirtimePurchaseResponse;
use GenioForge\Consumer\Data\AbstractModels\TransactionResponse;
use GenioForge\Consumer\Data\Utils\Enums\TransactionStatus;

class PulseAirtimePurchaseResponse extends AirtimePurchaseResponse
{
    use PulseStatus, NetworkResponse;
    public function __construct(public Transaction $transaction)
    {
        parent::__construct($transaction->ref,
            $transaction->status == TransactionStatus::SUCCESS, $transaction->message, $transaction->toJson());
        $this->amount_charged = $this->transaction->amountPaid;
        $this->fee = $this->transaction->amountPaid - $this->transaction->amount;
        $this->client_reference = $this->transaction->clientRef;
        $this->airtime_amount = $this->transaction->amount;
        $this->mobile_no = $this->transaction->details['phone_number']??'';
    }
}
