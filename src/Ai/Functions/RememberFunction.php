<?php

namespace Lenorix\LaravelAiMemory\Ai\Functions;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Lenorix\LaravelAiMemory\Models\AiMemory;
use MalteKuhr\LaravelGPT\GPTFunction;

class RememberFunction extends GPTFunction
{
    public function __construct(
        protected ?Model $memorableScope = null,
        protected string $scopeName = 'global',
    ) {}

    /**
     * Specifies a function to be invoked by the model. The function is implemented as a
     * Closure which may take parameters that are provided by the model. If extra arguments
     * are included in the documentation to optimize model's performance (by allowing it more
     * thinking time), these can be disregarded by not including them within the Closure
     * parameters.
     *
     * If the Closure returns null, the chat interaction is paused until the 'send()' method in
     * the request is invoked again. For all other return values, the response is JSON encoded
     * and forwarded to the model for further processing.
     */
    public function function(): Closure
    {
        return function ($queries): mixed {
            Log::info("Accessing AI memory in scope $this->scopeName");

            return AiMemory::where('memorable', $this->memorableScope)
                ->where(function ($query) use ($queries) {
                    foreach ($queries as $queryString) {
                        $query->orWhere('content', 'like', "%$queryString%");
                    }
                })
                ->get();
        };
    }

    /**
     * Defines the rules for input validation and JSON schema generation. Override this
     * method to provide custom validation rules for the function. The documentation will
     * have the same order as the rules are defined in this method.
     */
    public function rules(): array
    {
        return [
            'queries' => 'array|min:1',
            'queries.*' => 'string|max:100',
        ];
    }

    /**
     * Describes the purpose and functionality of the GPT function. This is utilized
     * for generating the function documentation.
     */
    public function description(): string
    {
        return "Search for memories (in $this->scopeName scope) related to the given queries.";
    }
}
