<?php

namespace MTechOrg\Gitstamp;

use Illuminate\Support\ServiceProvider;
use MTechOrg\Gitstamp\Commands\GenerateGitstampCommand;

class GitstampServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/gitstamp.php', 'gitstamp');

        $this->app->singleton(GitReader::class);
        $this->app->singleton(Gitstamp::class);
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'gitstamp');

        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateGitstampCommand::class,
            ]);

            $this->publishes([
                __DIR__.'/../config/gitstamp.php' => config_path('gitstamp.php'),
            ], 'gitstamp-config');
        }
    }
}
