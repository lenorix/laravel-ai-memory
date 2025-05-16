<?php

namespace Lenorix\AiMemory\Commands;

use Illuminate\Console\Command;

class AiMemoryCommand extends Command
{
    public $signature = 'laravel-ai-memory';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
