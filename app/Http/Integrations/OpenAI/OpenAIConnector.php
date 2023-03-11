<?php

namespace App\Http\Integrations\OpenAI;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;

class OpenAIConnector extends Connector
{
    use AcceptsJson;

    public function resolveBaseUrl(): string
    {
        return 'https://api.openai.com/v1';
    }

    public function __construct()
    {
        $this->withTokenAuth(config('services.openai.secret'));
    }
    /**
     * Default headers for every request
     *
     * @return string[]
     */
    protected function defaultHeaders(): array
    {
        return [];
    }

    /**
     * Default HTTP client options
     *
     * @return string[]
     */
    protected function defaultConfig(): array
    {
        return [];
    }
}
