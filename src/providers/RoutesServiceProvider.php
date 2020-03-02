<?php

namespace BSC\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class RoutesServiceProvider extends BaseServiceProvider
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
    }
}
