<?php

namespace GenioForge\Consumer\Data\Pulse\Models;


use GenioForge\Consumer\Data\AbstractModels\ElectricityBillResponse;
use GenioForge\Consumer\Data\AbstractModels\TransactionResponse;
use GenioForge\Consumer\Data\Utils\Enums\TransactionStatus;

class PulseElectricityBillResponse extends ElectricityBillResponse
{
    use PulseStatus, NetworkResponse;
    public function __construct(public Transaction $transaction)
    {
        parent::__construct($transaction->ref,
            $transaction->status == TransactionStatus::SUCCESS, $transaction->message, $transaction->toJson());
        $this->amount_charged = $this->transaction->amountPaid;
        $this->fee = $this->transaction->amountPaid - $this->transaction->amount;
        $this->client_reference = $this->transaction->clientRef;

        $this->meter_number = $this->transaction->details['meter_number']??null;
        $this->customer_name = $this->transaction->details['name']??null;
        $this->customer_phone = $this->transaction->details['phone']??null;
        $this->address = $this->transaction->details['address']??null;
        $this->token_amount = $this->transaction->details['token_amount']??null;
        $this->token_value = $this->transaction->details['token_value']??null;
        $this->tariff = $this->transaction->details['tariff']??null;
        $this->units = $this->transaction->details['units']??null;
        $this->meter_type = $this->transaction->details['meter_type']??null;
        $this->token = $this->transaction->details['token']??null;

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
