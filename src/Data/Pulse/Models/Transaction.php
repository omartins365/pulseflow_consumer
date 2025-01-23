<?php

namespace GenioForge\Consumer\Data\Pulse\Models;

use GenioForge\Consumer\Data\Utils\Enums\TransactionStatus;

class Transaction
{
    public function __construct(
        public int $id,
        public string $ref,
        public string $desc,
        public TransactionStatus $status,
        public ?float $amount,
        public ?float $amountPaid,
        public ?float $amountReceived,
        public ?string $gateway,
        public ?string $providerRef,
        public string $currency,
        public int $customerId,
        public ?string $paymentMethod,
        public ?int $cardId,
        public ?int $productId,
        public array $details,
        public ?string $message,
        public ?string $logo,
        public string $createdAt,
        public string $updatedAt,
        public ?string $clientRef,
    ) {}

    public static function fromJson(array $json): self
    {
        return new self(
            id: $json['id'],
            ref: $json['ref'],
            desc: $json['desc'],
            status: $json['status'] instanceof TransactionStatus? $json['status'] : TransactionStatus::tryFrom($json['status']),
            amount: $json['amount'],
            amountPaid: $json['amount_paid'],
            amountReceived: $json['amount_received'],
            gateway: $json['gateway'],
            providerRef: $json['provider_ref'],
            currency: $json['currency'],
            customerId: $json['customer_id'],
            paymentMethod: $json['payment_method'],
            cardId: $json['card_id'],
            productId: $json['product_id'],
            details: $json['details'],
            message: $json['message'],
            logo: $json['logo'],
            createdAt: $json['created_at'],
            updatedAt: $json['updated_at'],
            clientRef: $json['clientRef']??null,
        );
    }

    public function toJson(): array
    {
        return [
            'id' => $this->id,
            'ref' => $this->ref,
            'desc' => $this->desc,
            'status' => $this->status->value,
            'amount' => $this->amount,
            'amount_paid' => $this->amountPaid,
            'amount_received' => $this->amountReceived,
            'gateway' => $this->gateway,
            'provider_ref' => $this->providerRef,
            'currency' => $this->currency,
            'customer_id' => $this->customerId,
            'payment_method' => $this->paymentMethod,
            'card_id' => $this->cardId,
            'product_id' => $this->productId,
            'details' => $this->details,
            'message' => $this->message,
            'logo' => $this->logo,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
            'clientRef' => $this->clientRef,
        ];
    }

    public function __toString(): string
    {
        return "Transaction {$this->ref} : {$this->toJson()}";
    }
}
