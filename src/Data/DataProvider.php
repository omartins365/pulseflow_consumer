<?php

namespace GenioForge\Consumer\Data;

use GenioForge\Consumer\Data\AbstractModels\AirtimePurchaseResponse;
use GenioForge\Consumer\Data\AbstractModels\ApiResponse;
use GenioForge\Consumer\Data\AbstractModels\ElectricityBillResponse;
use GenioForge\Consumer\Data\AbstractModels\PinPurchaseResponse;
use GenioForge\Consumer\Data\AbstractModels\TransactionResponse;
use GenioForge\Consumer\Data\AbstractModels\WalletBalance;
use GenioForge\Consumer\Data\Utils\Enums\DataProviderAttribute;

interface DataProvider
{
    public function __toString(): String;

    static public function identity(): string;
    static public function label(): string;
    public function ensure_initialised();
    public function wallet_balance(): WalletBalance;
    public function verify_transaction($reference, $args = []): TransactionResponse;
    public function buy_data($reference, $plan_id, $network_id, $phone_number): TransactionResponse;
    public function buy_data_pin($reference, $network_id, $plan_id, $quantity): PinPurchaseResponse;
    public function buy_airtime_pin($reference, $network_id, $plan_id, $quantity): PinPurchaseResponse;
    public function buy_airtime($reference, $plan_id, $network_id, $phone_number, $amount): AirtimePurchaseResponse;
    public function buy_exam_card($reference, $exam_type, $quantity, $plan_id = null):PinPurchaseResponse;
    public function pay_cable($reference, $company_id, $plan_id, $smart_card_no): TransactionResponse;
    public function pay_electricity($reference, $company_id, $plan_id, $meter_no, $amount): ElectricityBillResponse;
    public function verify_smart_card_no($reference, $company_id, $smart_card_no): ApiResponse;
    public function verify_meter_no($reference, $company_id, $plan_id, $meter_no, $amount): ApiResponse;
    public function fetch_price_list(): ?array;
    public function can(DataProviderAttribute $ability):bool ;
    public function cannot(DataProviderAttribute $ability):bool ;
}
