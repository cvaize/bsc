<?php

namespace BSC\App\Models\Products;

use BSC\Database\Migrate;
use BSC\Database\MigrateInterface;
use BSC\App\Models\Model;
use Illuminate\Database\Schema\Blueprint;


class ProductsToCategories extends Model implements MigrateInterface
{
	protected $table = 'bsc_products_to_categories';

	protected $fillable = [
		'product_id',
		'category_id',
		'ordering',
	];

	public $timestamps = false;

	public function migrate(): void
	{
		(new Migrate($this->getTable()))->createOrTable(function (Blueprint $table, Migrate $migrate){
			// Тут только создание колонок, никаких индексов и связей
			// Создание тут связей/индексов повлечет за собой ошибку
			$migrate->createOrChange($table->unsignedBigInteger('product_id'));
			$migrate->createOrChange($table->unsignedInteger('category_id'));
			$migrate->createOrChange($table->unsignedBigInteger('ordering')->default(0));
		}, function (Blueprint $table, Migrate $migrate){
			$product = new \BscProduct();
			$category = new \BscCategory();

			$migrate->foreign('product_id', function ($foreign) use ($product){
				$foreign->references($product->getKeyName())->on($product->getTable())
					->onUpdate('cascade')->onDelete('cascade');
			});
			$migrate->foreign('category_id', function ($foreign) use ($category){
				$foreign->references($category->getKeyName())->on($category->getTable())
					->onUpdate('cascade')->onDelete('cascade');
			});
		});
	}
}
