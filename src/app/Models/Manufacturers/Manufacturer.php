<?php

namespace BSC\App\Models\Manufacturers;

use BSC\Database\Migrate;
use BSC\Database\MigrateInterface;
use BSC\App\Models\Model;
use Illuminate\Database\Schema\Blueprint;


class Manufacturer extends Model implements MigrateInterface
{
	protected $table = 'manufacturers';

	protected $perPage = 20;

	protected $fillable = [
		'name',
		'ordering',
		'is_publish',
	];

	public function migrate(): void
	{
		(new Migrate($this->getTable()))->createOrTable(function (Blueprint $table, Migrate $migrate){
			// Тут только создание колонок, никаких индексов и связей
			// Создание тут связей/индексов повлечет за собой ошибку
			$migrate->createOrChange($table->increments('id'));
			$migrate->createOrChange($table->unsignedInteger('ordering')->default(0));
			$migrate->createOrChange($table->string('name'));
			$migrate->createOrChange($table->boolean('is_publish')->default(true));
			!$migrate->hasColumn('created_at') && $table->timestamps();
		});
	}
}
