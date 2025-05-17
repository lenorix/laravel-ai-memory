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

    public function function(): Closure
    {
        return function (array $contents): mixed {
            Log::info("Creating AI memory in $this->scopeName scope.");

            $result = [];
            foreach ($contents as $content) {
                if (AiMemory::where('content', $content)
                    ->where('memorable_type', $this->memorableScope?->getMorphClass())
                    ->where('memorable_id', $this->memorableScope?->getKey())
                    ->exists()) {
                    Log::debug("Memory already in $this->scopeName exists.");
                    continue;
                }

                $result[] = AiMemory::create([
                    'content' => $content,
                    'memorable' => $this->memorableScope,
                ]);
                Log::debug("Memory created in $this->scopeName scope.");
            }
            return $result;
        };
    }

    public function rules(): array
    {
        return [
            'contents' => 'required|array',
            'contents.*' => 'required|string|max:5000|min:15',
        ];
    }

    public function description(): string
    {
        return "Store facts extracted from the user’s input (in $this->scopeName memory) to support future reasoning across conversations. Any fact must be stored.";
    }

    public function name(): string
    {
        return 'rememberFact';
    }
}
