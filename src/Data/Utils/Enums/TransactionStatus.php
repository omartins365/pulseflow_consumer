<?php
namespace GenioForge\Consumer\Data\Utils\Enums;

enum TransactionStatus: int
{
    case NEW = 0;
    case PENDING = 1;
    case SUCCESS = 2;
    case PAID_LESS = 3;
    case PAID_MORE = 4;
    case FAILED = 5;
    case ABANDONED = 6;
    case INVALID = 7;
    case FUND_INSUFFICIENT = 8;
    case NOT_PROCESSED = 9;
    case REFUNDED = 10;
    case DUPLICATE_CANCELED = 11;
    case PAYMENT_PENDING = 12;
    case REFUND_IN_PROGRESS = 13;
    case RETRIED = 14;
}
