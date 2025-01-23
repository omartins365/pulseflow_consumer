<?php

namespace GenioForge\Consumer\Data\Pulse\Models;

use GenioForge\Consumer\Data\Utils\Enums\TransactionStatus;

trait PulseStatus
{
    public function was_successful(): ?bool
    {
        return $this->transaction->status == TransactionStatus::SUCCESS;
    }

    public function was_refunded(): ?bool
    {
        return $this->transaction->status == TransactionStatus::REFUNDED;
    }
    public function is_pending(): ?bool
    {
        return $this->transaction->status == TransactionStatus::NEW || $this->transaction->status == TransactionStatus::PENDING;
    }

    public function has_failed(): ?bool
    {
        return $this->transaction->status == TransactionStatus::FAILED
            || $this->transaction->status == TransactionStatus::ABANDONED
            || $this->transaction->status == TransactionStatus::DUPLICATE_CANCELED
            || $this->transaction->status == TransactionStatus::INVALID
            || $this->transaction->status == TransactionStatus::REFUNDED
            || $this->transaction->status == TransactionStatus::NOT_PROCESSED
            || $this->transaction->status == TransactionStatus::FUND_INSUFFICIENT;
    }

}
