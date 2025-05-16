<?php

namespace Lenorix\LaravelAiMemory\Ai\Functions;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Lenorix\AiMemory\Models\AiMemory;
use MalteKuhr\LaravelGPT\GPTFunction;

class ForgetFunction extends GPTFunction
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
        return function ($id): mixed {
            Log::info("Deleting AI memory with ID $id in scope $this->scopeName");

            return AiMemory::where('memorable', $this->memorableScope)
                ->where('id', $id)
                ->delete();
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
            'id' => config('ai-memory.use_ulid') ? 'required|ulid' : 'required|integer',
        ];
    }

    /**
     * Describes the purpose and functionality of the GPT function. This is utilized
     * for generating the function documentation.
     */
    public function description(): string
    {
        return "Forget a specific memory (in $this->scopeName scope) by its ID. This function allows you to remove a memory permanently.";
    }
}
