<?php

namespace GenioForge\Consumer\Data\Pulse\Models;

class Service
{
    public function __construct(
        public int $id,
        public string $name,
        public string $desc,
        public string $icon,
        public ?string $materialIcon,
        public bool $qtyIsFixed,
    ) {}

    public static function fromJson(array $json): self
    {
        return new self(
            id: $json['id'],
            name: $json['name'],
            desc: $json['desc'],
            icon: $json['icon'],
            materialIcon: $json['material_icon'],
            qtyIsFixed: $json['qty_is_fixed'],
        );
    }

    public function toJson(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'desc' => $this->desc,
            'icon' => $this->icon,
            'material_icon' => $this->materialIcon,
            'qty_is_fixed' => $this->qtyIsFixed,
        ];
    }

    public function __toString(): string
    {
        return "Service {$this->id} : {$this->name}";
    }
}
