<?php

namespace GenioForge\Consumer\Data\Pulse;

use GenioForge\Consumer\Data\Pulse\PulseClient;

class PulseFlow extends PulseClient
{
    public function __construct()
    {
        $vendorDomain = config('consumer.api.pulse.domain');
        $apiKey = config('consumer.api.pulse.key');
        $basePath = "api/v1";
        parent::__construct($vendorDomain, $apiKey, $basePath);
        $this->customerPin = config('consumer.api.pulse.pin');
    }
    static public function identity(): string
    {
        return 'PulseFlowAPI';
    }

    static public function label(): string
    {
        return 'PulseFlowAPI';
    }

}
