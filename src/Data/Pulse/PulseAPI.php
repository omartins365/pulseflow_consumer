<?php

namespace GenioForge\Consumer\Data\Pulse;

class PulseAPI extends PulseClient
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
        return 'PulseAPI';
    }

    static public function label(): string
    {
        return 'PulseAPI';
    }

}
