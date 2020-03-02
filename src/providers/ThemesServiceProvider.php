<?php

namespace BSC\Providers;

use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ThemesServiceProvider extends BaseServiceProvider
{
	/**
	 * Привязка к контейнеру.
	 *
	 * @return void
	 */
    public function register()
    {
        $this->mergeConfigFrom($this->configPath(), 'themes');
    }

	/**
	 * Загрузка сервисов после регистрации.
	 *
	 * @return void
	 */
    public function boot()
    {
		$views = [
			resource_path('views/themes/'.config('themes.theme', 'custom')),
			__DIR__ . '/../resources/views',
		];

		$this->loadViewsFrom($views, 'theme');

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
