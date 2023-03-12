<?php

namespace App\Http\Integrations\OpenAI\Requests;

use App\Http\Integrations\OpenAI\OpenAIConnector;
use Illuminate\Support\Collection;
use Saloon\Contracts\Body\BodyRepository;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use Saloon\Traits\Request\HasConnector;

class SuggestAttendeesRequest extends Request implements HasBody
{
    use HasJsonBody, HasConnector;

    protected string $connector = OpenAIConnector::class;
    protected string $title;
    protected string $description;
    protected Collection $people;

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
    public function __construct(string $title, string $description, Collection $people)
    {
        $this->title = $title;
        $this->description = $description;
        $this->people = $people;
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
        $prompt = "Based on the following meeting info, and the skills and departments of the following people. Who would you advise to attend the meeting and why, please pick the best 5 and then return them in a JSON object with as key the name of the user, and as value the reason why you chose them, put \" around the key to make it valid json \n\n meeting title: {$this->title}, \n\n meeting description: {$this->description}. \n\n People:";

        $this->people->each(function ($person) use (&$prompt) {
            $prompt .= "{$person->name} - {$person->departments->pluck('name')->join(', ')} - {$person->skills->pluck('name')->join(', ')}";
        });

        $prompt .= "\n\nPlease add json below:";

        return [
            "model" => "text-davinci-003",
            "prompt" => $prompt,
            "max_tokens" => 1250,
        ];
    }
}
