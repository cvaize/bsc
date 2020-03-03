<?php

namespace BSC\App\Console;

use BSC\Database\MigrateInterface;
use Illuminate\Console\Command;

class MigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bsc:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Миграции приложения';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
    	$models = config('define.models');
    	foreach ($models as $modelString){
			$model = new $modelString();
			if($model instanceof MigrateInterface){
				$model->migrate();
				dump('Migrated: '.$modelString);
			}
		}
    }
}
