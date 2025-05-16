<?php

namespace Lenorix\AiMemory;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Lenorix\AiMemory\Commands\AiMemoryCommand;

class AiMemoryServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('ai-memory')
            ->hasConfigFile()
            /*->hasViews()*/
            ->hasMigration('create_ai_memory_table')
            /*->hasCommand(AiMemoryCommand::class)*/;
    }
}
