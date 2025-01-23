<?php

namespace GenioForge\Consumer\Data\Pulse\Models;

class PaginatedModels
{
    public function __construct(
        public array $models,
        public int $currentPage,
        public int $lastPage,
    ) {}

    public static function fromJson(array $json, callable $fromJson): self
    {
        return new self(
            models: array_map($fromJson, $json['data']),
            currentPage: $json['current_page'],
            lastPage: $json['last_page'],
        );
    }

    public function __toString(): string
    {
        return "Page : {$this->currentPage} \n {$this->models}";
    }
}
