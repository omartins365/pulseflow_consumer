<?php

namespace GenioForge\Consumer\Data\Pulse\Models;

class WalletEntry
{
    public function __construct(
        public int $id,
        public string $ref,
        public string $desc,
        public float $amount,
        public string $currency,
        public int $customerId,
        public int $productId,
        public int $status,
        public string $createdAt,
        public string $updatedAt,
    ) {}

    public static function fromJson(array $json): self
    {
        return new self(
            id: $json['id'],
            ref: $json['ref'],
            desc: $json['desc'],
            amount: $json['amount'],
            currency: $json['currency'],
            customerId: $json['customer_id'],
            productId: $json['product_id'],
            status: $json['status'],
            createdAt: $json['created_at'],
            updatedAt: $json['updated_at'],
        );
    }

    public function toJson(): array
    {
        return [
            'id' => $this->id,
            'ref' => $this->ref,
            'desc' => $this->desc,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'customer_id' => $this->customerId,
            'product_id' => $this->productId,
            'status' => $this->status,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }

    public function __toString(): string
    {
        return "WalletEntry {$this->ref} : {$this->amount} {$this->currency}";
    }
}
