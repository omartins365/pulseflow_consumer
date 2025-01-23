<?php

namespace GenioForge\Consumer\Data\Pulse\Models;

use GenioForge\Consumer\Data\Utils\Enums\TransactionStatus;

trait NetworkResponse
{
//    public function network_response(): string
//    {
//        return $this->transaction->details['message']??'';
//    }
    public function network_response(): string
    {

        if (isset($this->transaction) && $this->transaction->status == TransactionStatus::FUND_INSUFFICIENT){
            return "E02: Product is temporarily unavailable, contact support if message persist!";
        }

        $parent_response = method_exists(get_parent_class($this),'network_response')?parent::network_response():$this->message??'';
        return str($parent_response??"")
            ->replace("Insufficient fund","E02b: Product is temporarily unavailable, contact admin if message persist!",false)
            ->replace("Pin confirmation is required", 'E03: Service is unavailable at the moment',false)
            ->replace("Invalid Pin", 'E01: Service is unavailable at the moment',false)
            ->trim();
    }
}
