<?php

namespace BSC\App\Models\Attrs;

use BSC\Database\Migrate;
use BSC\Database\MigrateInterface;
use BSC\App\Models\Model;
use Illuminate\Database\Schema\Blueprint;


class AttrValue extends Model implements MigrateInterface
{
	protected $table = 'bsc_attrs_values';

	protected $fillable = [
		'name',
		'ordering',
		'attr_id',
	];

	public function migrate(): void
	{
		(new Migrate($this->getTable()))->createOrTable(function (Blueprint $table, Migrate $migrate){
			// Тут только создание колонок, никаких индексов и связей
			// Создание тут связей/индексов повлечет за собой ошибку
			$migrate->createOrChange($table->increments('id'));
			$migrate->createOrChange($table->string('name'));
			$migrate->createOrChange($table->unsignedInteger('ordering')->default(0));
			$migrate->createOrChange($table->unsignedInteger('attr_id'));
		}, function (Blueprint $table, Migrate $migrate){
			$attr = new \BscAttr();
			$migrate->foreign('attr_id', function ($foreign) use ($attr){
				$foreign->references($attr->getKeyName())->on($attr->getTable())
					->onUpdate('cascade')->onDelete('cascade');
			});
		});
	}

	public $timestamps = false;
}
