<?php

namespace App\Http\Integrations\OpenAI\Requests;

use App\Http\Integrations\OpenAI\OpenAIConnector;
use Saloon\Contracts\Body\BodyRepository;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use Saloon\Traits\Request\HasConnector;

class ShouldThisBeAnEmailRequest extends Request implements HasBody
{
    use HasJsonBody, HasConnector;

    protected string $connector = OpenAIConnector::class;
    protected string $title;
    protected string $description;

    /**
     * Define the HTTP method
     *
     * @var Method
     */
    protected Method $method = Method::POST;

    /**
     * @param string $title
     * @param string $description
     */
    public function __construct(string $title, string $description)
    {
        $this->title = $title;
        $this->description = $description;
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json'
        ];
    }

    /**
     * Define the endpoint for the request
     *
     * @return string
     */
    public function resolveEndpoint(): string
    {
        return '/completions';
    }

    protected function defaultBody(): array
    {
        return [
            "model" => "text-davinci-003",
            "prompt" => "Based on the following meeting info, should this be an meeting or an email? Title: {$this->title} Description: {$this->description}, Please explain why or why not",
            "max_tokens" => 50,
        ];
    }
}
