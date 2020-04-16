<?php

namespace BSC\Providers;

use BSC\App\Console\MigrateCommand;
use BSC\Database\Types\UnsignedTinyInteger;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\Facades\Schema;
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
		$this->mergeConfigFrom($this->configPath(), 'bsc');

		$loader = \Illuminate\Foundation\AliasLoader::getInstance();
		$aliases = config('bsc.aliases', []);
		foreach ($aliases as $class=>$alias){
			$loader->alias($class, $alias);
		}
    }

	/**
	 * Загрузка сервисов после регистрации.
	 *
	 * @return void
	 */
    public function boot()
    {
		if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
			$this->publishes([$this->configPath() => config_path('bsc.php')], 'bsc');

			/**
			 * Fix https://github.com/laravel/framework/issues/8840
			 */
			Schema::registerCustomDoctrineType(
				UnsignedTinyInteger::class,
				UnsignedTinyInteger::NAME,
				'TINYINT UNSIGNED'
			);

			$this->commands([
				MigrateCommand::class,
			]);
		}

		$views = [
			resource_path('views/themes/'.config('bsc.theme', 'custom')),
			__DIR__ . '/../resources/views',
		];

		$this->loadViewsFrom($views, 'theme');

		$this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }
	/**
	 * Set the config path
	 *
	 * @return string
	 */
	protected function configPath()
	{
		return __DIR__ . '/../config/bsc.php';
	}
}
