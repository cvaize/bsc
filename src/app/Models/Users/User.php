<?php

namespace BSC\App\Models\Users;

use BSC\Database\Migrate;
use BSC\Database\MigrateInterface;
use BSC\App\Models\Model;
use Illuminate\Database\Schema\Blueprint;


class User extends Model implements MigrateInterface
{
	protected $table = 'users';

	protected $fillable = [
		'unique_name',
		'f_name',
		'l_name',
		'm_name',
		'email',
		'phone',
		'payment_id',
		'shipping_id',

//		'full_address', // лучше вынести адреса в отдельную таблицу, так как у пользователя может быть множество адресов
//		'street',
//		'street_nr',
//		'home',
//		'apartment',
//		'zip',
//		'city',
//		'state',
//		'country',
//		'lang', // лучше на клиенте определять язык, если человек в первый раз зашел на сайт
	];

	public function migrate(): void
	{
		(new Migrate($this->getTable()))->createOrTable(function (Blueprint $table, Migrate $migrate){
			// Тут только создание колонок, никаких индексов и связей
			// Создание тут связей/индексов повлечет за собой ошибку
			$migrate->createOrChange($table->bigIncrements('id'));
			$migrate->createOrChange($table->string('unique_name')->nullable());
			$migrate->createOrChange($table->string('f_name')->nullable());
			$migrate->createOrChange($table->string('l_name')->nullable());
			$migrate->createOrChange($table->string('m_name')->nullable());
			$migrate->createOrChange($table->string('email')->nullable());
			$migrate->createOrChange($table->string('phone', 24)->nullable());
			$migrate->createOrChange($table->unsignedTinyInteger('payment_id')->nullable());
			$migrate->createOrChange($table->unsignedTinyInteger('shipping_id')->nullable());
			!$migrate->hasColumn('created_at') && $table->timestamps();
		}, function (Blueprint $table, Migrate $migrate){
			$migrate->unique(['unique_name']);
			$migrate->index(['email']);
			$migrate->index(['phone']);
		});
	}
}
