<?php

namespace GenioForge\Consumer\Data\Pulse\Models;

class Beneficiary
{
    public function __construct(
        public int $id,
        public int $customerId,
        public int $categoryId,
        public string $name,
        public ?string $customName,
        public string $label,
        public string $desc,
        public string $value,
    ) {}

    public static function fromJson(array $json): self
    {
        return new self(
            id: $json['id'],
            customerId: $json['customer_id'],
            categoryId: $json['category_id'],
            name: $json['name'],
            customName: $json['custom_name'],
            label: $json['label'],
            desc: $json['desc'],
            value: $json['value'],
        );
    }

    public function toJson(): array
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customerId,
            'category_id' => $this->categoryId,
            'name' => $this->name,
            'custom_name' => $this->customName,
            'label' => $this->label,
            'desc' => $this->desc,
            'value' => $this->value,
        ];
    }

    public function __toString(): string
    {
        return "Beneficiary {$this->name} : {$this->value}";
    }
}
