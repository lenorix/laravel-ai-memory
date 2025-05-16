<?php

namespace Lenorix\LaravelAiMemory\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AiMemory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'content',
    ];

    public function memorable(): MorphTo
    {
        return $this->morphTo();
    }
}
