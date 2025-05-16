<?php

namespace Lenorix\LaravelAiMemory\Ai\Functions;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Lenorix\LaravelAiMemory\Models\AiMemory;
use MalteKuhr\LaravelGPT\GPTFunction;

class MemorizeFunction extends GPTFunction
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
        return function ($content): mixed {
            Log::info("Creating AI memory in scope $this->scopeName");

            return AiMemory::create([
                'content' => $content,
                'memorable' => $this->memorableScope,
            ]);
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
            'content' => 'required|string|max:1000',
        ];
    }

    /**
     * Describes the purpose and functionality of the GPT function. This is utilized
     * for generating the function documentation.
     */
    public function description(): string
    {
        return "Memorize (in $this->scopeName scope) anything for future usage searching for it.";
    }
}
