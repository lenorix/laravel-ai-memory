<?php

namespace Lenorix\AiMemory\Ai\Functions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Lenorix\AiMemory\Models\AiMemory;
use MalteKuhr\LaravelGPT\GPTFunction;
use Closure;

class UpdateMemoryFunction extends GPTFunction
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
     *
     * @return Closure
     */
    public function function(): Closure
    {
        return function ($id, $content): mixed {
            Log::info("Updating AI memory with ID $id in scope $this->scopeName");
            return AiMemory::where('memorable', $this->memorableScope)
                ->where('id', $id)
                ->update([
                    'content' => $content,
                ]);
        };
    }

    /**
     * Defines the rules for input validation and JSON schema generation. Override this
     * method to provide custom validation rules for the function. The documentation will
     * have the same order as the rules are defined in this method.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'id' => config('ai-memory.use_ulid') ? 'required|ulid' : 'required|integer',
            'content' => 'required|string|max:1000',
        ];
    }

    /**
     * Describes the purpose and functionality of the GPT function. This is utilized
     * for generating the function documentation.
     *
     * @return string
     */
    public function description(): string
    {
        return "Update a specific memory (in $this->scopeName scope) by its ID and new content.";
    }
}
