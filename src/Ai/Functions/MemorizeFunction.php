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
            Log::info("Creating AI memory in scope $this->scopeName");

            $result = [];
            foreach ($contents as $content) {
                $result[] = AiMemory::create([
                    'content' => $content,
                    'memorable' => $this->memorableScope,
                ]);
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
