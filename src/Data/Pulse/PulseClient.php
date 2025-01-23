<?php

namespace GenioForge\Consumer\Data\Pulse;

use GenioForge\Consumer\Data\AbstractModels\AirtimePurchaseResponse;
use GenioForge\Consumer\Data\AbstractModels\ApiResponse;
use GenioForge\Consumer\Data\AbstractModels\ElectricityBillResponse;
use GenioForge\Consumer\Data\AbstractModels\PinPurchaseResponse;
use GenioForge\Consumer\Data\AbstractModels\TransactionResponse;
use GenioForge\Consumer\Data\AbstractModels\WalletBalance;
use GenioForge\Consumer\Data\DataProvider;
use GenioForge\Consumer\Data\Pulse\Models\PulseAirtimePurchaseResponse;
use GenioForge\Consumer\Data\Pulse\Models\PulseElectricityBillResponse;
use GenioForge\Consumer\Data\Pulse\Models\PulseElectricityCustomerResponse;
use GenioForge\Consumer\Data\Pulse\Models\PulsePinPurchaseResponse;
use GenioForge\Consumer\Data\Pulse\Models\PulseTransactionResponse;
use GenioForge\Consumer\Data\Pulse\Models\PulseTvCustomerResponse;
use GenioForge\Consumer\Data\Pulse\Models\PulseWalletBalance;
use GenioForge\Consumer\Data\Pulse\Pulse;
use GenioForge\Consumer\Exceptions\ApiNotInitialisedException;
use GenioForge\Consumer\Exceptions\UnableToGetWalletBalanceException;
use Illuminate\Support\Facades\Http;

class PulseClient extends Pulse implements DataProvider
{

    public function __construct($vendorDomain, $apiKey, $basePath = "api/v1", protected string $customerPin = '')
    {
        parent::__construct($vendorDomain, $apiKey, $basePath);
    }

    public function __toString(): string
    {
        // TODO: Implement __toString() method.
    }

    static public function identity(): string
    {
        // TODO: Implement identity() method.
    }

    static public function label(): string
    {
        // TODO: Implement label() method.
    }

    public function ensure_initialised()
    {
        if (empty($this->apiKey) || empty($this->customerPin)) {
            throw new ApiNotInitialisedException();
        }
    }

    public function wallet_balance(): WalletBalance
    {
        $this->ensure_initialised();
        try {
            $customer = $this->getCustomer($this->customerId??'');
            $wallet_balance = PulseWalletBalance::fromResponse($customer);
            return $wallet_balance;
        } catch (\Throwable $th) {
            throw new UnableToGetWalletBalanceException($th);
        }
    }

    public function verify_transaction($reference, $args = []): TransactionResponse
    {
        $tx = $this->verifyTransaction($reference);
        return new PulseTransactionResponse($tx);
    }

    public function buy_data($reference, $plan_id, $network_id, $phone_number): TransactionResponse
    {
        $tx = $this->purchaseProduct($plan_id,[
            'reference' => $reference,
            'phone_number' => $phone_number,
        ], $this->customerPin);
        return new PulseTransactionResponse($tx);
    }

    public function buy_data_pin($reference, $network_id, $plan_id, $quantity): PinPurchaseResponse
    {
        $tx = $this->purchaseProduct($plan_id,[
            'reference' => $reference,
            'quantity' => $quantity,
        ], $this->customerPin);
        return new PulsePinPurchaseResponse($tx);
    }

    public function buy_airtime_pin($reference, $network_id, $plan_id, $quantity): PinPurchaseResponse
    {
        $tx = $this->purchaseProduct($plan_id,[
            'reference' => $reference,
            'quantity' => $quantity,
        ], $this->customerPin);
        return new PulsePinPurchaseResponse($tx);
    }

    public function buy_airtime($reference, $plan_id, $network_id, $phone_number, $amount): AirtimePurchaseResponse
    {
        $tx = $this->purchaseProduct($plan_id,[
            'reference' => $reference,
            'phone_number' => $phone_number,
            'amount' => $amount,
        ], $this->customerPin);
        return new PulseAirtimePurchaseResponse($tx);
    }

    public function buy_exam_card($reference, $exam_type, $quantity, $plan_id = null): PinPurchaseResponse
    {
        $tx = $this->purchaseProduct($plan_id,[
            'reference' => $reference,
            'quantity' => $quantity,
        ], $this->customerPin);
        return new PulsePinPurchaseResponse($tx);
    }

    public function pay_cable($reference, $company_id, $plan_id, $smart_card_no): TransactionResponse
    {
        $tx = $this->purchaseProduct($plan_id,[
            'reference' => $reference,
            'smart_card_number' => $smart_card_no,
        ], $this->customerPin);
        return new PulseTransactionResponse($tx);
    }

    public function pay_electricity($reference, $company_id, $plan_id, $meter_no, $amount): ElectricityBillResponse
    {
        $tx = $this->purchaseProduct($plan_id,[
            'reference' => $reference,
            'meter_number' => $meter_no,
            'amount' => $amount,
        ], $this->customerPin);
        return new PulseElectricityBillResponse($tx);
    }

    public function verify_smart_card_no($reference, $company_id, $smart_card_no): ApiResponse
    {
        $details = $this->verifyUtilityId($company_id, $smart_card_no);
        return new PulseTvCustomerResponse($details);
    }

    public function verify_meter_no($reference, $company_id, $plan_id, $meter_no, $amount): PulseElectricityCustomerResponse
    {
        $details = $this->verifyUtilityId($company_id, $meter_no, [
            'product_id' => $plan_id,
            'amount' => $amount,
        ]);
        return new PulseElectricityCustomerResponse($details);
    }

    public function fetch_price_list(): ?array
    {
        // TODO: Implement fetch_price_list() method.
        // use the fetch products endpoint
    }
}
