<?php

namespace GenioForge\Consumer\Data\Pulse;

use GenioForge\Consumer\Data\Pulse\Exceptions\ApiException;
use GenioForge\Consumer\Data\Traits\CanDoDataProviderAttribute;
use GenioForge\Consumer\Data\Traits\Logger;
use GenioForge\Consumer\Data\Utils\Enums\DataProviderAttribute;
use GenioForge\Consumer\Exceptions\CustomerDoesNotExistException;
use GenioForge\Consumer\Exceptions\EmailCannotBeEmptyLoginException;
use GenioForge\Consumer\Exceptions\PasswordCannotBeEmptyLoginException;
use GenioForge\Consumer\Data\Pulse\Models\ApiResponse;
use GenioForge\Consumer\Data\Pulse\Models\Beneficiary;
use GenioForge\Consumer\Data\Pulse\Models\Card;
use GenioForge\Consumer\Data\Pulse\Models\Category;
use GenioForge\Consumer\Data\Pulse\Models\Customer;
use GenioForge\Consumer\Data\Pulse\Models\PaginatedModels;
use GenioForge\Consumer\Data\Pulse\Models\Product;
use GenioForge\Consumer\Data\Pulse\Models\Service;
use GenioForge\Consumer\Data\Pulse\Models\Transaction;
use GenioForge\Consumer\Data\Pulse\Models\WalletAccount;
use GenioForge\Consumer\Data\Pulse\Models\WalletEntry;
use GenioForge\Consumer\Data\Utils\Enums\TransactionStatus;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Str;

/**
 *
 */
class UnauthenticatedApiException extends ApiException {}

/**
 *
 */
class MethodNotAllowedApiException extends ApiException {}

/**
 *
 */
class ResourceNotFoundApiException extends ApiException {}

/**
 *
 */
class MissingAbilityApiException extends ApiException {}

/**
 *
 */
class ApiValidationException extends ApiException {}

/**
 *
 */
class ApiRequestFailure extends ApiException {}

/**
 *
 */
class Pulse
{
    use CanDoDataProviderAttribute, Logger;
    protected $features = [
        DataProviderAttribute::CheckWalletBalance,
        DataProviderAttribute::BuyData,
        DataProviderAttribute::BuyDataPin,
        DataProviderAttribute::BuyAirtime,
        DataProviderAttribute::BuyAirtimePin,
        DataProviderAttribute::BuyResultChecker,
        DataProviderAttribute::PayElectricBill,
        DataProviderAttribute::SubscribeToCable,
        DataProviderAttribute::VerifyTransaction,
        DataProviderAttribute::VerifyCableSmartCard,
        DataProviderAttribute::VerifyElectricMeter,
        DataProviderAttribute::FetchProductList,
    ];
    /**
     * @var Client
     */
    private $httpClient;
    /**
     * @var
     */
    private $vendorDomain;
    /**
     * @var mixed|string
     */
    private $basePath;
    /**
     * @var
     */
    protected $apiKey;

    /**
     * @param $vendorDomain
     * @param $apiKey
     * @param $basePath
     */
    public function __construct($vendorDomain, $apiKey, $basePath = "api/v1")
    {
        $this->httpClient = new Client();
        $this->vendorDomain = $vendorDomain;
        $this->basePath = $basePath;
        $this->apiKey = $apiKey;
    }

    /**
     * @return string[]
     */
    private function getHeaders()
    {
        $useApiKey = $this->apiKey ?? 'defaultApiKey';
        return [
//            'Host' => config('app.domain'),
//            'User-Agent' => 'PostmanRuntime/7.29.4',
            'Authorization' => 'Bearer ' . $useApiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    /**
     * @throws GuzzleException
     * @throws ApiRequestFailure
     */
    protected function sendRequest($method = 'get', $endPoint = '', $data = null):ApiResponse
    {
        try {
            $this->log()->debug('Sending request',[
                'endPoint' => $endPoint,
                'method' => $method,
                'body' => $data
            ]);
            $response = null;

            if ($method === 'get') {
                $apiRequest = $this->vendorDomain . '/' . $this->basePath . '/' . $endPoint;
                if (!empty($data)){
                    $apiRequest.=  '?' . http_build_query($data);
                }
                $response = $this->httpClient->get($apiRequest, ['headers' => $this->getHeaders()]);
            } elseif ($method === 'post' || $method === 'put') {
                $apiRequest = $this->vendorDomain . '/' . $this->basePath . '/' . $endPoint;

                if ($method === 'put') {
                    $data = array_merge($data, ['_method' => 'PUT']);
                }

                $response = $this->httpClient->post($apiRequest, [
                    'headers' => $this->getHeaders(),
                    'json' => $data,
                ]);
            } else {
                throw new \Exception('Method has not been implemented');
            }

            $responseData = json_decode($response->getBody(), true);
            $this->log()->debug('Request completed \n',[
                        'endPoint' => $endPoint,
                        'method' => $method,
//                        'body' => $data ,
                        'status' => $response->getStatusCode(),
//                        'raw' => $response->getBody(),
                        'response' => $responseData,
                    ]
                    );
            $apiResponse =  ApiResponse::fromJson($responseData);

            if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
                throw new ApiException($apiResponse);
            }

            return $apiResponse;
        } catch (ApiException $e) {
//            dd($e);
                throw $e;
        } catch (ClientException $e){
            $body = json_decode($e->getResponse()->getBody(), true);
            $this->log()->debug('Request pending \n',[
                        'endPoint' => $endPoint,
                        'method' => $method,
//                        'body' => $data ,
                        'error' => $e,
                        'response' => $body,
                    ]);
            if (!
            (
                isset($body['status'])
                && isset($body['code'])
                && isset($body['message'])
            )
            ){
                throw $e;
            }
            throw new ApiRequestFailure(ApiResponse::fromJson($body));
        } catch (\Exception $e){
            $this->log()->debug('Request failed \n',[
                        'endPoint' => $endPoint,
                        'method' => $method,
                        'error' => $e,
                    ]);
            throw new ApiRequestFailure(ApiResponse::fromJson([
                'status' => 'Error',
                'code' => 400,
                'message' => $e->getMessage(),
            ]));
        }
    }

    /**
     * @param $serviceId
     * @return Service
     * @throws ApiRequestFailure
     * @throws GuzzleException
     */
    public function getService($serviceId)
    {
        $response = $this->sendRequest('get', 'services/' . $serviceId);
        $data = $response->data;

        return Service::fromJson($data);
    }

    /**
     * @return array|Service[]
     * @throws ApiRequestFailure
     * @throws GuzzleException
     */
    public function getServices()
    {
        $response = $this->sendRequest('get', 'services');
        $services = $response->data;

        return array_map(function ($serviceData) {
            return Service::fromJson($serviceData);
        }, $services);
    }

    /**
     * @param $serviceId
     * @param $page
     * @return PaginatedModels
     * @throws ApiRequestFailure
     * @throws GuzzleException
     */
    public function getCategories($serviceId, $page = 1)
    {
        $response = $this->sendRequest('get', 'services/' . $serviceId . '/categories', ['page' => $page]);
        $data = $response->data;

        return PaginatedModels::fromJson($data, function ($json) {
            return Category::fromJson($json);
        });
    }

    /**
     * @param $serviceId
     * @return array[Category]
     * @throws ApiRequestFailure
     * @throws GuzzleException
     */
    public function getAllCategories($serviceId)
    {
        $categories = [];
        $currentPage = 1;
        $lastPage = 1;

        do {
            $paginatedCategories = $this->getCategories($serviceId, $currentPage);
            $categories = array_merge($categories, $paginatedCategories->models);
            $currentPage = $paginatedCategories->currentPage + 1;
            $lastPage = $paginatedCategories->lastPage;
        } while ($currentPage <= $lastPage);

        return $categories;
    }

    /**
     * @param $serviceId
     * @param $categoryId
     * @return Category
     * @throws ApiRequestFailure
     * @throws GuzzleException
     */
    public function getCategory($serviceId, $categoryId)
    {
        $response = $this->sendRequest('get', 'services/' . $serviceId . '/categories/' . $categoryId);
        $data = $response->data;

        return Category::fromJson($data);
    }

    /**
     * @param $serviceId
     * @param $categoryId
     * @param $page
     * @return PaginatedModels
     * @throws ApiRequestFailure
     * @throws GuzzleException
     */
    public function getProducts($serviceId, $categoryId, $page = 1)
    {
        $response = $this->sendRequest('get', 'services/' . $serviceId . '/categories/' . $categoryId . '/products', ['page' => $page]);
        $data = $response->data;

        return PaginatedModels::fromJson($data, function ($json) {
            return Product::fromJson($json);
        });
    }

    /**
     * @param $page
     * @return PaginatedModels
     * @throws ApiRequestFailure
     * @throws GuzzleException
     */
    public function getTransactions($page = 1)
    {
        $response = $this->sendRequest('get', 'customers/transactions', ['page' => $page]);
        $data = $response->data;

        return PaginatedModels::fromJson($data, function ($json) {
            return Transaction::fromJson($json);
        });
    }

    /**
     * @param $page
     * @return PaginatedModels
     * @throws ApiRequestFailure
     * @throws GuzzleException
     */
    public function getWalletEntries($page = 1)
    {
        $response = $this->sendRequest('get', 'customers/accounts', ['page' => $page]);
        $data = $response->data;

        return PaginatedModels::fromJson($data, function ($json) {
            return WalletEntry::fromJson($json);
        });
    }

    /**
     * @param $page
     * @return PaginatedModels
     * @throws ApiRequestFailure
     * @throws GuzzleException
     */
    public function getBeneficiaries($page = 1)
    {
        $response = $this->sendRequest('get', 'customers/beneficiaries', ['page' => $page]);
        $data = $response->data;

        return PaginatedModels::fromJson($data, function ($json) {
            return Beneficiary::fromJson($json);
        });
    }

    /**
     * @param $beneficiaryId
     * @param $customName
     * @return Beneficiary
     * @throws ApiRequestFailure
     * @throws GuzzleException
     */
    public function updateBeneficiary($beneficiaryId, $customName)
    {
        $response = $this->sendRequest('post', 'customers/beneficiaries/' . $beneficiaryId, ['custom_name' => $customName]);
        $data = $response->data;

        return Beneficiary::fromJson($data);
    }

    /**
     * @param $serviceId
     * @param $categoryId
     * @return array
     * @throws ApiRequestFailure
     * @throws GuzzleException
     */
    public function getAllProducts($serviceId, $categoryId)
    {
        $products = [];
        $currentPage = 1;
        $lastPage = 1;

        do {
            $paginatedProducts = $this->getProducts($serviceId, $categoryId, $currentPage);
            $products = array_merge($products, $paginatedProducts->models);
            $currentPage = $paginatedProducts->currentPage + 1;
            $lastPage = $paginatedProducts->lastPage;
        } while ($currentPage <= $lastPage);

        return $products;
    }

    /**
     * @param $productId
     * @return Product
     * @throws ApiRequestFailure
     * @throws GuzzleException
     */
    public function getProduct($productId)
    {
        $response = $this->sendRequest('get', 'products/' . $productId);
        $data = $response->data;

        return Product::fromJson($data);
    }

    /**
     * @param $customerId
     * @return Customer
     * @throws ApiRequestFailure
     * @throws GuzzleException
     */
    public function getCustomer($customerId)
    {
        $response = $this->sendRequest('get', 'customers/' . $customerId);
        $data = $response->data;

        return Customer::fromJson($data);
    }

    /**
     * @param $customerData
     * @param $password
     * @return Customer
     * @throws ApiRequestFailure
     * @throws GuzzleException
     */
    public function createCustomer($customerData, $password)
    {
        $response = $this->sendRequest('post', 'customers', [
           ...$customerData, 'password' => $password
        ]);
        $data = $response->data;

        return Customer::fromJson($data);
    }

    /**
     * @param $customerData
     * @param $password
     * @param $pin
     * @return Customer
     * @throws ApiRequestFailure
     * @throws CustomerDoesNotExistException
     * @throws GuzzleException
     */
    public function updateCustomer($customerData, $password = null, $pin = null)
    {
        if (!isset($customerData['id'])) {
            throw new CustomerDoesNotExistException();
        }

        $response = $this->sendRequest('put', 'customers/' . $customerData['id'], [
           ...$customerData, 'password' => $password, 'pin' => $pin,
        ]);
        $data = $response->data;

        return Customer::fromJson($data);
    }

    /**
     * @param $email
     * @param $password
     * @return Customer
     * @throws ApiRequestFailure
     * @throws EmailCannotBeEmptyLoginException
     * @throws GuzzleException
     * @throws PasswordCannotBeEmptyLoginException
     */
    public function customerLogin($email, $password)
    {
        if (empty($email)) {
            throw new EmailCannotBeEmptyLoginException();
        }
        if (empty($password)) {
            throw new PasswordCannotBeEmptyLoginException();
        }

        $response = $this->sendRequest('post', 'customers/login', [
           'email' => $email, 'password' => $password
        ]);
        $data = $response->data;

        return Customer::fromJson($data);
    }

    /**
     * @return array|Card[]
     * @throws ApiRequestFailure
     * @throws GuzzleException
     */
    public function getCards()
    {
        $response = $this->sendRequest('get', 'customers/cards');
        $cards = $response->data;

        return array_map(function ($cardData) {
            return Card::fromJson($cardData);
        }, $cards);
    }

    /**
     * @return array|WalletAccount[]
     * @throws ApiRequestFailure
     * @throws GuzzleException
     */
    public function getWalletAccounts()
    {
        $response = $this->sendRequest('get', 'customers/reserved_accounts');
        $walletAccounts = $response->data;

        return array_map(function ($account) {
            return WalletAccount::fromJson($account);
        }, $walletAccounts);
    }

    /**
     * @param $provider
     * @param $token
     * @return Customer
     * @throws ApiRequestFailure
     * @throws GuzzleException
     */
    public function authenticateWithProvider($provider, $token)
    {
        $response = $this->sendRequest('post', 'customers/auth', [

                'provider' => $provider,
                'token' => $token,

        ]);
        $data = $response->data;

        return Customer::fromJson($data);
    }

    /**
     * @param $email
     * @param $otp
     * @param $resetPassword
     * @return Customer
     * @throws ApiRequestFailure
     * @throws GuzzleException
     */
    public function confirmOTP($email, $otp, $resetPassword = false)
    {
        $response = $this->sendRequest('post', 'customers/auth/otp/verify',[
                'email' => $email,
                'otp' => $otp,
                'reset_password' => $resetPassword,
        ]);
        $data = $response->data;

        return Customer::fromJson($data);
    }

    /**
     * @param $email
     * @return bool
     * @throws ApiRequestFailure
     * @throws GuzzleException
     */
    public function sendOTP($email)
    {
        $response = $this->sendRequest('post', 'customers/auth/otp/send',['email' => $email]);
        $data = $response->code;

        return $data == 200;
    }

    /**
     * @param $password
     * @return bool
     * @throws ApiRequestFailure
     * @throws GuzzleException
     */
    public function confirmPassword($password)
    {
        $response = $this->sendRequest('post', 'customers/auth/password/confirm', ['password' => $password]);
        $data = $response->code;

        return $data == 200;
    }

    /**
     * @param $password
     * @return mixed|null
     * @throws ApiRequestFailure
     * @throws GuzzleException
     */
    public function useBiometrics($password)
    {
        $response = $this->sendRequest('post', 'customers/auth/biometrics',['password' => $password]);
        $data = $response->data;

        return $data['token'] ?? null;
    }

    /**
     * @param $email
     * @return bool
     * @throws ApiRequestFailure
     * @throws GuzzleException
     */
    public function sendFlutterwaveOTP($email)
    {
        $response = $this->sendRequest('post', 'customers/auth/otp/send', ['email' => $email
        ]);
        $data = $response->code;

        return $data == 200;
    }

    /**
     * @param $amount
     * @return Transaction
     * @throws ApiRequestFailure
     * @throws GuzzleException
     */
    public function initializeFunding($amount)
    {
        $response = $this->sendRequest('get', 'transactions/init/funding', ['amount' => $amount]);
        $data = $response->data;

        return Transaction::fromJson($data);
    }

    /**
     * @param $ref
     * @return Transaction
     * @throws ApiRequestFailure
     * @throws GuzzleException
     */
    public function verifyTransaction($ref)
    {
        $response = $this->sendRequest('get', 'transactions/verify',[
            'reference' => $ref,
        ]);
        $data = $response->data;

        return Transaction::fromJson($data);
    }

    /**
     * @param $categoryId
     * @param $idNumber
     * @param $parameters
     * @param $serviceId
     * @return array
     * @throws ApiRequestFailure
     * @throws GuzzleException
     */
    public function verifyUtilityId($categoryId, $idNumber, $parameters = null, $serviceId = 'utilities')
    {
        $response = $this->sendRequest('get', 'services/' . $serviceId . '/categories/' . $categoryId . '/verify/' . $idNumber,  $parameters);
        $data = $response->data;

        return $data;
    }

    /**
     * @param $productId
     * @param $parameters
     * @param $pin
     * @return Transaction
     * @throws ApiRequestFailure
     * @throws GuzzleException
     */
    public function purchaseProduct($productId, $parameters, $pin): Transaction
    {
        $response = $this->sendRequest('post', 'transactions/purchase/' . $productId,
             [
                'pin' => $pin,
                'parameters' => $parameters,
            ]
        );
        $data = $response->data;
        $tx = Transaction::fromJson($data);
        if (empty($tx->message)){
            $tx->message = $this->transaction->details['message']??Str::title($tx->status->name);
        }
        return $tx;
    }
}
