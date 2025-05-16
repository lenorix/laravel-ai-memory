<?php

namespace Lenorix\LaravelAiMemory\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Lenorix\LaravelAiMemory\AiMemory
 */
class AiMemory extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Lenorix\LaravelAiMemory\AiMemory::class;
    }
}
