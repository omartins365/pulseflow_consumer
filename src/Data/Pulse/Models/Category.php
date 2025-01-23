<?php

namespace GenioForge\Consumer\Data\Pulse\Models;

class Category
{
    public function __construct(
        public int $id,
        public int $serviceId,
        public string $name,
        public string $desc,
        public bool $discountBased,
        public ?string $logo,
    ) {}

    public static function fromJson(array $json): self
    {
        return new self(
            id: $json['id'],
            serviceId: $json['service_id'],
            name: $json['name'],
            desc: $json['desc'],
            discountBased: $json['discount_based'],
            logo: $json['logo'],
        );
    }

    public function toJson(): array
    {
        return [
            'id' => $this->id,
            'service_id' => $this->serviceId,
            'name' => $this->name,
            'desc' => $this->desc,
            'discount_based' => $this->discountBased,
            'logo' => $this->logo,
        ];
    }

    public function __toString(): string
    {
        return "{$this->serviceId} Category {$this->id} : {$this->name}";
    }
}
