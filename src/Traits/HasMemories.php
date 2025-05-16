<?php

namespace Lenorix\LaravelAiMemory\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Lenorix\LaravelAiMemory\Models\AiMemory;

trait HasMemories
{
    public function memories(): MorphMany
    {
        return $this->morphMany(AiMemory::class, 'memorable')->chaperone();
    }
}
