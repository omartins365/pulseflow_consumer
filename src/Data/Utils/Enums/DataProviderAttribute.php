<?php
namespace GenioForge\Consumer\Data\Utils\Enums;

enum DataProviderAttribute
{
    case CheckWalletBalance;
    case FetchProductList;
    case VerifyTransaction;
    case VerifyTransactionByClientRef;
    case BuyData;
    case BuyAirtime;
    case BuyDataPin;
    case BuyAirtimePin;
    case SubscribeToCable;
    case PayElectricBill;
    case BuyResultChecker;
    case VerifyCableSmartCard;
    case VerifyElectricMeter;
}
