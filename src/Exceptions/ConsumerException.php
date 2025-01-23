<?php

namespace GenioForge\Consumer\Exceptions;


class ConsumerException extends \Exception
{

}

class UnableToGetWalletBalanceException extends ConsumerException
{
}

class ApiNotInitialisedException extends ConsumerException
{
}

class PurchaseNotSuccessfulException extends ConsumerException
{
}

class PurchaseWasRefundedException extends ConsumerException{

}

class PurchaseResponseIsPendingException extends ConsumerException{

}

class UnableToGetTransactionResponseException extends ConsumerException{

}

class BadResponseException extends ConsumerException{

}

class RepositoryNotInitialisedException extends ConsumerException{}

class ProviderNotFoundException extends ConsumerException{}

class CustomerDoesNotExistException extends ConsumerException {}
class EmailCannotBeEmptyLoginException extends ConsumerException {}
class PasswordCannotBeEmptyLoginException extends ConsumerException {}
