<?php

namespace GenioForge\Consumer\Data\Pulse\Models;

class Product
{
    public function __construct(
        public int $id,
        public int $serviceId,
        public int $categoryId,
        public string $name,
        public string $longName,
        public ?string $validFor,
        public string $desc,
        public float $priceInNaira,
        public float $discountInNaira,
        public float $amountInNaira,
        public bool $isAvailable,
        public ?string $logo,
        public ?float $percentDiscount,
    ) {}

    public static function fromJson(array $json): self
    {
        return new self(
            id: $json['id'],
            serviceId: $json['service_id'],
            categoryId: $json['category_id'],
            name: $json['name'],
            longName: $json['long_name'],
            validFor: $json['valid_for'],
            desc: $json['desc'],
            priceInNaira: $json['price_in_naira'],
            discountInNaira: $json['discount_in_naira'],
            amountInNaira: $json['amount_in_naira'],
            isAvailable: $json['is_available'],
            logo: $json['logo'],
            percentDiscount: $json['percent_discount']??0,
        );
    }

    public function toJson(): array
    {
        return [
            'id' => $this->id,
            'service_id' => $this->serviceId,
            'category_id' => $this->categoryId,
            'name' => $this->name,
            'long_name' => $this->longName,
            'valid_for' => $this->validFor,
            'desc' => $this->desc,
            'price_in_naira' => $this->priceInNaira,
            'discount_in_naira' => $this->discountInNaira,
            'amount_in_naira' => $this->amountInNaira,
            'is_available' => $this->isAvailable,
            'logo' => $this->logo,
            'percent_discount' => $this->percentDiscount,
        ];
    }

    public function __toString(): string
    {
        return "{$this->serviceId} > ({$this->categoryId}) Product {$this->id} : {$this->name}";
    }
}
