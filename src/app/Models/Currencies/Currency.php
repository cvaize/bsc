<?php

namespace BSC\App\Models\Currencies;

use BSC\Database\Migrate;
use BSC\Database\MigrateInterface;
use BSC\App\Models\Model;
use Illuminate\Database\Schema\Blueprint;


class Currency extends Model implements MigrateInterface
{
	protected $table = 'currencies';

	protected $fillable = [
		'name',
		'ordering',
		'code',
		'code_iso',
		'code_num',
		'value',
		'is_publish',
	];

	public function migrate(): void
	{
		(new Migrate($this->getTable()))->createOrTable(function (Blueprint $table, Migrate $migrate){
			// Тут только создание колонок, никаких индексов и связей
			// Создание тут связей/индексов повлечет за собой ошибку
			$migrate->createOrChange($table->increments('id'));
			$migrate->createOrChange($table->string('name', 64));
			$migrate->createOrChange($table->string('code', 20));
			$migrate->createOrChange($table->string('code_iso', 4));
			$migrate->createOrChange($table->string('code_num', 3));
			$migrate->createOrChange($table->unsignedInteger('ordering')->default(0));
			$migrate->createOrChange($table->decimal('value', 14, 6)->default(1));
			$migrate->createOrChange($table->boolean('is_publish')->default(true));
		});
	}

	public $timestamps = false;
}
