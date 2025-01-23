<?php

namespace GenioForge\Consumer\Data\Pulse\Models;

class WalletAccount
{
    public function __construct(
        public int $id,
        public string $ref,
        public int $customerId,
        public string $name,
        public string $number,
        public string $bankName,
        public string $bankCode,
        public string $currency,
        public string $gateway,
        public string $createdAt,
        public string $updatedAt,
    ) {}

    public static function fromJson(array $json): self
    {
        return new self(
            id: $json['id'],
            ref: $json['ref'],
            customerId: $json['customer_id'],
            name: $json['name'],
            number: $json['number'],
            bankName: $json['bank_name'],
            bankCode: $json['bank_code'],
            currency: $json['currency'],
            gateway: $json['gateway'],
            createdAt: $json['created_at'],
            updatedAt: $json['updated_at'],
        );
    }

    public function toJson(): array
    {
        return [
            'id' => $this->id,
            'ref' => $this->ref,
            'customer_id' => $this->customerId,
            'name' => $this->name,
            'number' => $this->number,
            'bank_name' => $this->bankName,
            'bank_code' => $this->bankCode,
            'currency' => $this->currency,
            'gateway' => $this->gateway,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }

    public function __toString(): string
    {
        return "WalletAccount {$this->bankName} {$this->number} : {$this->toJson()}";
    }
}
