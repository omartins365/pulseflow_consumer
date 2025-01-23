<?php

namespace GenioForge\Consumer\Repository;

use GenioForge\Consumer\Data\DataProvider;
use GenioForge\Consumer\Data\AbstractModels\WalletBalance;
use GenioForge\Consumer\Data\AbstractModels\PinPurchaseResponse;
use GenioForge\Consumer\Data\AbstractModels\TransactionResponse;
use GenioForge\Consumer\Data\AbstractModels\AirtimePurchaseResponse;
use GenioForge\Consumer\Data\AbstractModels\ElectricityBillResponse;
use GenioForge\Consumer\Data\Pulse\PulseFlow;
use GenioForge\Consumer\Data\Utils\Enums\DataProviderAttribute;
use GenioForge\Consumer\Exceptions\ProviderNotFoundException;
use GenioForge\Consumer\Exceptions\RepositoryNotInitialisedException;

/**
 *
 */
class RepositoryProvider implements DataProvider
{
    /**
     * @var DataProvider|null
     */
    static public ?DataProvider $provider;

  


    /**
     */
    public function __construct(DataProvider $provider)
    {
        self::$provider = $provider;
    }

    /**
     * @return string
     */
    public function __toString(): String
    {
        return $this::class;
    }



    /**
     * @return string
     */
    static public function identity(): string
    {
        return self::$provider::identity();
    }

    /**
     * @return string
     */
    static public function label(): string
    {
        return self::$provider::label();
    }
    // public function

    /**
     * @return static
     */
    static public function defautProvider()
    {

        return new RepositoryProvider(self::getProviderClass());
    }

    /**
     * @return static
     */
    static public function getProvider(): static
    {
        return new RepositoryProvider(self::getProviderClass());
    }
    /**
     * @return static
     */
    static public function getProviderClass(): DataProvider
    {
       return new PulseFlow();
    }

    /**
     * @return void
     * @throws RepositoryNotInitialisedException
     */
    public function ensure_initialised()
    {
        if (isset(self::$provider)) {
            try {
                self::$provider->ensure_initialised();
            } catch (\Throwable $th) {
                throw new RepositoryNotInitialisedException($th);
            }
        } else {
            throw new RepositoryNotInitialisedException();
        }
    }
    /**
     * @return WalletBalance
     */
    public function wallet_balance(): WalletBalance
    {
        $this->ensure_initialised();
        return self::$provider->wallet_balance();
    }

    /**
     *
     * @param mixed $reference
     * @return TransactionResponse
     */
    public function verify_transaction($reference, $args = []): TransactionResponse
    {
        $this->ensure_initialised();
        return self::$provider->verify_transaction($reference, $args);
    }

    /**
     *
     * @param mixed $network_id
     * @param mixed $phone_number
     * @param mixed $plan_id
     * @param mixed $reference
     * @return TransactionResponse
     */
    public function buy_data($reference, $plan_id, $network_id, $phone_number): TransactionResponse
    {
        $this->ensure_initialised();
        return self::$provider->buy_data($reference, $plan_id, $network_id, $phone_number);
    }

    /**
     * @return array|null
     */
    public function fetch_price_list(): ?array
    {
        $this->ensure_initialised();
        return self::$provider->fetch_price_list();
    }

    /**
     * @param mixed $reference
     * @param mixed $network_id
     * @param mixed $plan_id
     * @param mixed $quantity
     * @return TransactionResponse
     */
    public function buy_data_pin($reference, $network_id, $plan_id, $quantity): PinPurchaseResponse
    {
        $this->ensure_initialised();
        return self::$provider->buy_data_pin($reference, $network_id, $plan_id, $quantity);
    }

    /**
     *
     * @param mixed $reference
     * @param mixed $company_id
     * @param mixed $plan_id
     * @param mixed $smart_card_no
     * @return TransactionResponse
     */
    public function pay_cable($reference, $company_id, $plan_id, $smart_card_no): TransactionResponse
    {
        $this->ensure_initialised();
        return self::$provider->pay_cable($reference, $company_id, $plan_id, $smart_card_no);
    }

    /**
     *
     * @param mixed $reference
     * @param mixed $company_id
     * @param mixed $smart_card_no
     * @return TransactionResponse
     */
    public function verify_smart_card_no($reference, $company_id, $smart_card_no): TransactionResponse
    {
        $this->ensure_initialised();
        return self::$provider->verify_smart_card_no($reference, $company_id, $smart_card_no);
    }

    /**
     *
     * @param mixed $reference
     * @param mixed $company_id
     * @param mixed $plan_id
     * @param mixed $meter_no
     * @param mixed $amount
     * @return TransactionResponse
     */
    public function verify_meter_no($reference, $company_id, $plan_id, $meter_no, $amount): ElectricityBillResponse
    {
        $this->ensure_initialised();
        return self::$provider->verify_meter_no($reference, $company_id, $plan_id, $meter_no, $amount);
    }

    /**
     *
     * @param mixed $reference
     * @param mixed $company_id
     * @param mixed $plan_id
     * @param mixed $meter_no
     * @param mixed $amount
     * @return TransactionResponse
     */
    public function pay_electricity($reference, $company_id, $plan_id, $meter_no, $amount): ElectricityBillResponse
    {
        $this->ensure_initialised();
        return self::$provider->pay_electricity($reference, $company_id, $plan_id, $meter_no, $amount);
    }

    /**
     * @param $reference
     * @param $plan_id
     * @param $network_id
     * @param $phone_number
     * @param $amount
     * @return AirtimePurchaseResponse
     * @throws RepositoryNotInitialisedException
     */
    public function buy_airtime($reference, $plan_id, $network_id, $phone_number, $amount): AirtimePurchaseResponse
    {
        $this->ensure_initialised();
        return self::$provider->buy_airtime($reference, $plan_id, $network_id, $phone_number, $amount);
    }

    /**
     * @param $reference
     * @param $network_id
     * @param $plan_id
     * @param $quantity
     * @return PinPurchaseResponse
     * @throws RepositoryNotInitialisedException
     */
    public function buy_airtime_pin($reference, $network_id, $plan_id, $quantity): PinPurchaseResponse
    {
        $this->ensure_initialised();
        return self::$provider->buy_airtime_pin($reference, $network_id, $plan_id, $quantity);
    }

    /**
     * @param $reference
     * @param $exam_type
     * @param $quantity
     * @return PinPurchaseResponse
     * @throws RepositoryNotInitialisedException
     */
    public function buy_exam_card($reference, $exam_type, $quantity, $plan_id = null): PinPurchaseResponse
    {
        $this->ensure_initialised();
        return self::$provider->buy_exam_card($reference, $exam_type, $quantity);
    }

    public function can(DataProviderAttribute $ability): bool
    {
        return self::$provider->can($ability);
    }

    public function cannot(DataProviderAttribute $ability): bool
    {
        return self::$provider->cannot($ability);
    }
}
