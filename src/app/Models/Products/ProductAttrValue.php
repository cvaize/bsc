<?php

namespace BSC\App\Models\Products;

use BSC\Database\Migrate;
use BSC\Database\MigrateInterface;
use BSC\App\Models\Model;
use Illuminate\Database\Schema\Blueprint;


class ProductAttrValue extends Model implements MigrateInterface
{
	protected $table = 'products_attrs_values';

	protected $fillable = [
		'product_attr_id',
		'attr_id',
		'attr_value_id',
	];

	public $timestamps = false;

	public function migrate(): void
	{
		(new Migrate($this->getTable()))->createOrTable(function (Blueprint $table, Migrate $migrate){
			// Тут только создание колонок, никаких индексов и связей
			// Создание тут связей/индексов повлечет за собой ошибку
			$migrate->createOrChange($table->unsignedBigInteger('product_attr_id'));
			$migrate->createOrChange($table->unsignedInteger('attr_id'));
			$migrate->createOrChange($table->unsignedInteger('attr_value_id'));
		}, function (Blueprint $table, Migrate $migrate){
			$productAttr = new \BscProductAttr();
			$attr = new \BscAttr();
			$attrValue = new \BscAttrValue();

			$migrate->foreign('product_attr_id', function ($foreign) use ($productAttr){
				$foreign->references($productAttr->getKeyName())->on($productAttr->getTable())
					->onUpdate('cascade')->onDelete('cascade');
			});
			$migrate->foreign('attr_id', function ($foreign) use ($attr){
				$foreign->references($attr->getKeyName())->on($attr->getTable())
					->onUpdate('cascade')->onDelete('cascade');
			});
			$migrate->foreign('attr_value_id', function ($foreign) use ($attrValue){
				$foreign->references($attrValue->getKeyName())->on($attrValue->getTable())
					->onUpdate('cascade')->onDelete('cascade');
			});
		});
	}
}
