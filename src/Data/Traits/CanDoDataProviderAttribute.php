<?php
namespace GenioForge\Consumer\Data\Traits;
use GenioForge\Consumer\Data\Utils\Enums\DataProviderAttribute;

trait CanDoDataProviderAttribute
{
    protected $default_features = [
        DataProviderAttribute::CheckWalletBalance,
    ];
    public function can(DataProviderAttribute $ability):bool
    {
        return in_array($ability, $this->features??$this->default_features);
    }

    public function cannot(DataProviderAttribute $ability):bool{
        return !$this->can($ability);
    }
}
