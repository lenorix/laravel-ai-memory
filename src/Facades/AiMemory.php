<?php

namespace Lenorix\AiMemory\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Lenorix\AiMemory\AiMemory
 */
class AiMemory extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Lenorix\AiMemory\AiMemory::class;
    }
}
