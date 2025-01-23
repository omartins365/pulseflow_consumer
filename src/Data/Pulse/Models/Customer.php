<?php

namespace GenioForge\Consumer\Data\Pulse\Models;

class Customer
{
    public function __construct(
        public int $id,
        public int $vendorId,
        public string $firstName,
        public string $lastName,
        public string $displayName,
        public string $email,
        public string $phone,
        public string $waPhone,
        public string $apiKey,
        public string $domain,
        public bool $hasPin,
        public bool $hasPassword,
        public bool $hasVerifiedEmail,
        public float $walletBalance,
    ) {}

    public static function fromJson(array $json): self
    {
        return new self(
            id: $json['id'],
            vendorId: $json['vendor_id'],
            firstName: $json['first_name'],
            lastName: $json['last_name'],
            displayName: $json['display_name'],
            email: $json['email'],
            phone: $json['phone'],
            waPhone: $json['wa_phone'],
            apiKey: $json['api_key'],
            domain: $json['domain'],
            hasPin: $json['has_pin'],
            hasPassword: $json['has_password'],
            hasVerifiedEmail: $json['has_verified_email'],
            walletBalance: $json['wallet_balance'],
        );
    }

    public function toJson(): array
    {
        return [
            'id' => $this->id,
            'vendor_id' => $this->vendorId,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'display_name' => $this->displayName,
            'email' => $this->email,
            'phone' => $this->phone,
            'wa_phone' => $this->waPhone,
            'api_key' => $this->apiKey,
            'domain' => $this->domain,
            'has_pin' => $this->hasPin,
            'has_password' => $this->hasPassword,
            'has_verified_email' => $this->hasVerifiedEmail,
            'wallet_balance' => $this->walletBalance,
        ];
    }

    public function __toString(): string
    {
        return "{$this->id} Customer {$this->firstName} {$this->lastName}";
    }
}
