<?php

namespace GenioForge\Consumer\Data\Pulse\Models;

class ApiResponse
{
    use NetworkResponse;

    public function __construct(
        public string $status,
        public int    $code,
        public string $message,
        public array  $data = [],
        public array  $error = [],
    )
    {
    }

    public static function fromJson(array $json): self
    {
        return new self(
            status: $json['status'],
            code: $json['code'],
            message: $json['message'],
            data: (array)($json['data'] ?? []),
            error: (array)($json['error'] ?? []),
        );
    }

    public function toJson(): array
    {
        return [
            'status' => $this->status,
            'code' => $this->code,
            'message' => $this->message,
            'data' => $this->data,
            'error' => $this->error,
        ];
    }
}
