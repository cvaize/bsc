<?php

namespace BSC\App\Models\Users;

use BSC\Database\Migrate;
use BSC\Database\MigrateInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;


class User extends Model implements MigrateInterface
{
	protected $table = 'bsc_users';

	protected $fillable = [
		'name',
	];

	public function migrate(){
		(new Migrate($this->getTable()))->createOrTable(function (Blueprint $table, Migrate $migrate){
			// Тут только создание колонок, никаких индексов и связей
			// Создание тут связей/индексов повлечет за собой ошибку
			$migrate->createOrChange($table->bigIncrements('id'));
			$migrate->createOrChange($table->string('name'));
			!$migrate->hasColumn('created_at') && $table->timestamps();
		}, function (Blueprint $table, Migrate $migrate){
			$migrate->index(['name']);
		});
	}
}
