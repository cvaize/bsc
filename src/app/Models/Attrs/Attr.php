<?php

namespace BSC\App\Models\Attrs;

use BSC\Database\Migrate;
use BSC\Database\MigrateInterface;
use BSC\App\Models\Model;
use Illuminate\Database\Schema\Blueprint;


class Attr extends Model implements MigrateInterface
{
	protected $table = 'attrs';

	protected $fillable = [
		'name',
		'ordering',
		'type_id', // тип: select/radio/
		'group_id',
		'is_all_cats',
		'is_use_placeholder',
		'placeholder',
	];

	public function migrate(): void
	{
		(new Migrate($this->getTable()))->createOrTable(function (Blueprint $table, Migrate $migrate){
			// Тут только создание колонок, никаких индексов и связей
			// Создание тут связей/индексов повлечет за собой ошибку
			$migrate->createOrChange($table->increments('id'));
			$migrate->createOrChange($table->string('name'));
			$migrate->createOrChange($table->string('placeholder')->comment('Заполнитель по умолчанию, например "Не выбрано"'));
			$migrate->createOrChange($table->unsignedInteger('ordering')->default(0));
			$migrate->createOrChange($table->unsignedTinyInteger('type_id')->default(1));
			$migrate->createOrChange($table->unsignedInteger('group_id')->nullable());
			$migrate->createOrChange($table->boolean('is_all_cats')->default(true));
			$migrate->createOrChange($table->boolean('is_use_placeholder')->default(true));
		}, function (Blueprint $table, Migrate $migrate){
			$attrGroup = new \BscAttrGroup();
			$migrate->foreign('group_id', function ($foreign) use ($attrGroup){
				$foreign->references($attrGroup->getKeyName())->on($attrGroup->getTable())
					->onUpdate('cascade')->onDelete('set null');
			});
		});
	}

	public $timestamps = false;

	protected $types = [
		1=>'Select',
		2=>'Radio'
	];

	public function getTypes(){
		return $this->types;
	}
}
