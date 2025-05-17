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

    public function function(): Closure
    {
        return function ($keywords): mixed {
            Log::info("Accessing AI memory in scope $this->scopeName");

            $memories = $this->memorableScope
                ? AiMemory::where('memorable_type', $this->memorableScope->getMorphClass())
                    ->where('memorable_id', $this->memorableScope->getKey())
                : AiMemory::whereNull('memorable_type')
                    ->whereNull('memorable_id');

            $memories = $memories
                ->where(function ($query) use ($keywords) {
                    foreach ($keywords as $keyword) {
                        $query->orWhere('content', 'like', "%$keyword%");
                    }
                })
                ->get();

            $total = $memories->count();
            Log::debug("Accessed $total AI memories in scope $this->scopeName");

            return $memories;
        };
    }

    public function rules(): array
    {
        return [
            'keywords' => 'array|min:1',
            'keywords.*' => 'string|max:30|min:2',
        ];
    }

    public function description(): string
    {
        return "Search the $this->scopeName memory for previously stored facts relevant to the given keywords or topics.";
    }

    public function name(): string
    {
        return 'searchRememberedFact';
    }
}
