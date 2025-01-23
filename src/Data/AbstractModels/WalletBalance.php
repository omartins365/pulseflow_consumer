<?php
namespace GenioForge\Consumer\Data\AbstractModels;
abstract class WalletBalance extends ApiResponse
{
    public $success;
    public $message;
    public $email;
    public $balance;
    public $checked_date;
    public $reference;
    public $status;

    
}
