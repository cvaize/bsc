<?php

namespace BSC\Providers;

use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class DefineServiceProvider extends BaseServiceProvider
{
	/**
	 * Привязка к контейнеру.
	 *
	 * @return void
	 */
    public function register()
    {
		$this->mergeConfigFrom($this->configPath(), 'define');
    }

	/**
	 * Загрузка сервисов после регистрации.
	 *
	 * @return void
	 */
    public function boot()
    {
		if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
			$this->publishes([$this->configPath() => config_path('define.php')], 'define');
		}
    }

	/**
	 * Set the config path
	 *
	 * @return string
	 */
	protected function configPath()
	{
		return __DIR__ . '/../config/define.php';
	}
}
