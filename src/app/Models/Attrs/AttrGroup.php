<?php

namespace BSC\App\Models\Attrs;

use BSC\Database\Migrate;
use BSC\Database\MigrateInterface;
use BSC\App\Models\Model;
use Illuminate\Database\Schema\Blueprint;


class AttrGroup extends Model implements MigrateInterface
{
	protected $table = 'bsc_attr_groups';

	protected $fillable = [
		'name',
		'ordering',
	];

	public function migrate(): void
	{
		(new Migrate($this->getTable()))->createOrTable(function (Blueprint $table, Migrate $migrate){
			// Тут только создание колонок, никаких индексов и связей
			// Создание тут связей/индексов повлечет за собой ошибку
			$migrate->createOrChange($table->increments('id'));
			$migrate->createOrChange($table->string('name'));
			$migrate->createOrChange($table->unsignedInteger('ordering')->default(0));
		});
	}

	public $timestamps = false;
}
