<?php
namespace ThankSong\Track123\Providers;

use Illuminate\Support\ServiceProvider;
use ThankSong\Track123\Track123;

class Track123ServiceProvider extends ServiceProvider{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \ThankSong\Track123\Console\Commands\GetCouriersCommand::class,
                \ThankSong\Track123\Console\Commands\QueryTrackingCommand::class,
            ]);
        }
        // 加载迁移
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        // 加载路由
        $this->loadRoutesFrom(__DIR__.'/../../routes/api.php');
        $this->publishes([
            __DIR__.'/../../config/track123.php' => config_path('track123.php'),
        ], 'track123');
        
    }

    public function register(){
        $this->mergeConfigFrom(
            __DIR__.'/../../config/track123.php',
            'track123'
        );
        $this->app->singleton('track123', fn() => new Track123);
    }
}