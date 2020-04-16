<?php

namespace BSC\App\Models\Products;

use BSC\Database\Migrate;
use BSC\Database\MigrateInterface;
use BSC\App\Models\Model;
use Illuminate\Database\Schema\Blueprint;


class ProductAttr extends Model implements MigrateInterface
{
	protected $table = 'products_attrs';

	protected $fillable = [
		'product_id',
		'ordering',
		'price',
		'count',
		'ean',
	];

	public $timestamps = false;

	public function migrate(): void
	{
		(new Migrate($this->getTable()))->createOrTable(function (Blueprint $table, Migrate $migrate){
			// Тут только создание колонок, никаких индексов и связей
			// Создание тут связей/индексов повлечет за собой ошибку
			$migrate->createOrChange($table->id('id'));
			$migrate->createOrChange($table->unsignedBigInteger('product_id'));
			$migrate->createOrChange($table->unsignedBigInteger('ordering')->default(0));
			$migrate->createOrChange($table->unsignedDecimal('price'));
			$migrate->createOrChange($table->unsignedInteger('count')->default(0));
			$migrate->createOrChange($table->string('ean', 32)->nullable());
		}, function (Blueprint $table, Migrate $migrate){
			$product = new \BscProduct();

			$migrate->foreign('product_id', function ($foreign) use ($product){
				$foreign->references($product->getKeyName())->on($product->getTable())
					->onUpdate('cascade')->onDelete('cascade');
			});
		});
	}
}
