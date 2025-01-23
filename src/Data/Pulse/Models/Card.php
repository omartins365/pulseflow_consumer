<?php

namespace GenioForge\Consumer\Data\Pulse\Models;

class Card
{
    public function __construct(
        public int $id,
        public int $customerId,
        public string $ref,
        public string $firstSixDigits,
        public string $lastFourDigits,
        public string $type,
        public string $issuer,
        public string $expiry,
    ) {}

    public static function fromJson(array $json): self
    {
        return new self(
            id: $json['id'],
            customerId: $json['customer_id'],
            ref: $json['ref'],
            firstSixDigits: $json['first_six_digits'],
            lastFourDigits: $json['last_four_digits'],
            type: $json['type'],
            issuer: $json['issuer'],
            expiry: $json['expiry'],
        );
    }

    public function toJson(): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customerId,
            'ref' => $this->ref,
            'first_six_digits' => $this->firstSixDigits,
            'last_four_digits' => $this->lastFourDigits,
            'type' => $this->type,
            'issuer' => $this->issuer,
            'expiry' => $this->expiry,
        ];
    }

    public function __toString(): string
    {
        return "{$this->customerId} Card {$this->id} : {$this->type}";
    }
}
