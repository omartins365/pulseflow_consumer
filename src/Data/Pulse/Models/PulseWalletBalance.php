<?php

namespace GenioForge\Consumer\Data\Pulse\Models;

use GenioForge\Consumer\Data\AbstractModels\WalletBalance;

class PulseWalletBalance extends WalletBalance
{
    public static function fromResponse($model){
        $pwb = new self($model->toJson());
        $pwb->balance = $pwb->response['wallet_balance'];
        return $pwb;
    }
}
