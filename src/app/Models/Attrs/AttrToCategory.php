<?php

namespace BSC\App\Models\Attrs;

use BSC\Database\Migrate;
use BSC\Database\MigrateInterface;
use BSC\App\Models\Model;
use Illuminate\Database\Schema\Blueprint;


class AttrToCategory extends Model implements MigrateInterface
{
	protected $table = 'bsc_attrs_to_categories';

	protected $fillable = [
		'attr_id',
		'category_id',
	];

	public $timestamps = false;

	public function migrate(): void
	{
		(new Migrate($this->getTable()))->createOrTable(function (Blueprint $table, Migrate $migrate){
			// Тут только создание колонок, никаких индексов и связей
			// Создание тут связей/индексов повлечет за собой ошибку
			$migrate->createOrChange($table->unsignedInteger('attr_id'));
			$migrate->createOrChange($table->unsignedInteger('category_id'));
		}, function (Blueprint $table, Migrate $migrate){
			$attr = new \BscAttr();
			$category = new \BscCategory();

			$migrate->foreign('attr_id', function ($foreign) use ($attr){
				$foreign->references($attr->getKeyName())->on($attr->getTable())
					->onUpdate('cascade')->onDelete('cascade');
			});
			$migrate->foreign('category_id', function ($foreign) use ($category){
				$foreign->references($category->getKeyName())->on($category->getTable())
					->onUpdate('cascade')->onDelete('cascade');
			});
		});
	}
}
