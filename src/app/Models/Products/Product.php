<?php

namespace BSC\App\Models\Products;

use BSC\Database\Migrate;
use BSC\Database\MigrateInterface;
use BSC\App\Models\Model;
use Illuminate\Database\Schema\Blueprint;


class Product extends Model implements MigrateInterface
{
	protected $table = 'bsc_products';

	protected $fillable = [
		'ordering',
		'name',
		'ean',
		'price',
		'old_price',
		'weight',
		'average_rating',
		'currency_id',
		'manufacturer_id',
		'quantity',
		'hits',
		'reviews_count',
		'is_publish',
		'is_unlimited',
	];

	public function migrate(): void
	{
		(new Migrate($this->getTable()))->createOrTable(function (Blueprint $table, Migrate $migrate){
			// Тут только создание колонок, никаких индексов и связей
			// Создание тут связей/индексов повлечет за собой ошибку
			$migrate->createOrChange($table->id('id'));
			$migrate->createOrChange($table->unsignedBigInteger('ordering')->default(0)->comment('Сортировка товаров.'));
			$migrate->createOrChange($table->string('name')->comment('Название товара, по умолчанию, '));
			$migrate->createOrChange($table->string('ean', 32)->nullable());
			$migrate->createOrChange($table->unsignedDecimal('price'));
			$migrate->createOrChange($table->unsignedDecimal('old_price')->nullable());
			$migrate->createOrChange($table->unsignedDecimal('weight')->nullable());
			$migrate->createOrChange($table->unsignedDecimal('average_rating', 4, 2));
			$migrate->createOrChange($table->unsignedInteger('currency_id')->nullable());
			$migrate->createOrChange($table->unsignedInteger('manufacturer_id')->nullable());
			$migrate->createOrChange($table->unsignedInteger('quantity')->default(1));
			$migrate->createOrChange($table->unsignedInteger('hits')->default(0));
			$migrate->createOrChange($table->unsignedInteger('reviews_count')->default(0));
			$migrate->createOrChange($table->boolean('is_publish')->default(true));
			$migrate->createOrChange($table->boolean('is_unlimited')->default(false));
			!$migrate->hasColumn('created_at') && $table->timestamps();
		}, function (Blueprint $table, Migrate $migrate){
			$manufacturer = new \BscManufacturer();
			$currency = new \BscCurrency();

			$migrate->unique(['ean']);
			$migrate->foreign('manufacturer_id', function ($foreign) use ($manufacturer){
				$foreign->references($manufacturer->getKeyName())->on($manufacturer->getTable())
					->onUpdate('cascade')->onDelete('set null');
			});
			$migrate->foreign('currency_id', function ($foreign) use ($currency){
				$foreign->references($currency->getKeyName())->on($currency->getTable())
					->onUpdate('cascade')->onDelete('set null');
			});
		});
	}
}
