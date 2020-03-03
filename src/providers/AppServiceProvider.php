<?php

namespace BSC\Providers;

use BSC\App\Console\MigrateCommand;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class AppServiceProvider extends BaseServiceProvider
{
	/**
	 * Привязка к контейнеру.
	 *
	 * @return void
	 */
    public function register()
    {
    	//
    }

	/**
	 * Загрузка сервисов после регистрации.
	 *
	 * @return void
	 */
    public function boot()
    {
		$this->loadRoutesFrom(__DIR__.'/../routes/web.php');
//		$this->loadMigrationsFrom(__DIR__.'/../database/migrations');
		if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
			$this->commands([
				MigrateCommand::class,
			]);
		}
    }
}
