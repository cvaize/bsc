<?php

namespace BSC\Providers;

use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ThemesServiceProvider extends BaseServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->configPath(), 'themes');

		$views = [
			resource_path('views/themes/'.config('themes.theme', 'custom')),
			__DIR__ . '/../resources/views',
		];

		$this->loadViewsFrom($views, 'theme');
    }

    /**
     * Register the config for publishing
     *
     */
    public function boot()
    {
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$this->configPath() => config_path('themes.php')], 'themes');
        }
    }

    /**
     * Set the config path
     *
     * @return string
     */
    protected function configPath()
    {
        return __DIR__ . '/../config/themes.php';
    }
}
