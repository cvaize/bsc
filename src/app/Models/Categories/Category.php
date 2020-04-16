<?php

namespace BSC\App\Models\Categories;

use BSC\Database\Migrate;
use BSC\Database\MigrateInterface;
use BSC\App\Models\Model;
use Illuminate\Database\Schema\Blueprint;


class Category extends Model implements MigrateInterface
{
	protected $table = 'categories';

	protected $perPage = 12;

	protected $fillable = [
		'name',
		'ordering',
		'is_publish',
		'parent_id',
		'lft',
		'rgt',
		'depth',
	];

	public function migrate(): void
	{
		(new Migrate($this->getTable()))->createOrTable(function (Blueprint $table, Migrate $migrate){
			// Тут только создание колонок, никаких индексов и связей
			// Создание тут связей/индексов повлечет за собой ошибку
			$migrate->createOrChange($table->increments('id'));
			$migrate->createOrChange($table->unsignedInteger('ordering')->default(0));
			$migrate->createOrChange($table->unsignedInteger('parent_id')->nullable());
			$migrate->createOrChange($table->unsignedInteger('lft')->nullable());
			$migrate->createOrChange($table->unsignedInteger('rgt')->nullable());
			$migrate->createOrChange($table->unsignedInteger('depth')->nullable());
			$migrate->createOrChange($table->string('name'));
			$migrate->createOrChange($table->boolean('is_publish')->default(true));
			!$migrate->hasColumn('created_at') && $table->timestamps();
		}, function (Blueprint $table, Migrate $migrate){
			$category = new \BscCategory();
			$migrate->foreign('parent_id', function ($foreign) use ($category){
				$foreign->references($category->getKeyName())->on($category->getTable())
					->onUpdate('cascade')->onDelete('cascade');
			});
		});
	}
}
