<?php

namespace BSC\App\Models\Pages;

use BSC\Database\Migrate;
use BSC\Database\MigrateInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;


class Page extends Model implements MigrateInterface
{
	protected $table = 'bsc_pages';

	protected $fillable = [
		'modelable_id', // Привязка к модели или товару
		'modelable_type',
		'alias',
		'path',
		'h1',
		'meta_title',
		'meta_description',
		'meta_keywords',
		'published_at',
		'created_at',
		'updated_at',
	];

	protected $casts = [
		'published_at'=>'datetime'
	];

	/**
	 * @param Builder $query
	 * @param array   $frd
	 *
	 * @return Builder
	 */
	public function scopeFilter(Builder $query, array $frd): Builder
	{
		$fillable = $this->fillable;
		foreach ($frd as $key => $value)
		{
			if ($value === null)
			{
				continue;
			}
			switch ($key)
			{
				case 'search':
					{
						$query->where(function ($query) use ($value) {
							$query->where('meta_title', 'like', '%' . $value . '%')
								->orWhere('meta_keywords', 'like', '%' . $value . '%')
								->orWhere('meta_description', 'like', '%' . $value . '%')
								->orWhere('alias', 'like', '%' . $value . '%')
								->orWhere('path', 'like', '%' . $value . '%')
								->orWhere('h1', 'like', '%' . $value . '%')
							;
						});
					}
					break;
				default:
					{
						if (in_array($key, $fillable))
						{
							$query->where($key, $value);
						}
					}
					break;
			}
		}

		return $query;
	}

	public function migrate(){
		(new Migrate($this->getTable()))->createOrTable(function (Blueprint $table, Migrate $migrate){
			// Тут только создание колонок, никаких индексов и связей
			// Создание тут связей/индексов повлечет за собой ошибку
			$migrate->createOrChange($table->bigIncrements('id'));
			$migrate->createOrChange($table->string('alias'));
			$migrate->createOrChange($table->string('path'));
			$migrate->createOrChange($table->string('h1')->nullable());
			$migrate->createOrChange($table->unsignedBigInteger('modelable_id')->nullable());
			$migrate->createOrChange($table->unsignedBigInteger('modelable1_id')->nullable());
			$migrate->createOrChange($table->unsignedBigInteger('modelable2_id')->nullable());
			$migrate->createOrChange($table->string('modelable_type')->nullable());
			$migrate->createOrChange($table->string('meta_title')->nullable());
			$migrate->createOrChange($table->string('meta_description')->nullable());
			$migrate->createOrChange($table->string('meta_keywords')->nullable());
			$migrate->createOrChange($table->dateTime('published_at')->nullable());
			!$migrate->hasColumn('created_at') && $table->timestamps();
		}, function (Blueprint $table, Migrate $migrate){
			$migrate->unique(['alias']);
			$migrate->unique(['path']);
			$migrate->unique(['h1']);
			$migrate->unique(['meta_title']);
			$migrate->index(['modelable_id']);
			$migrate->index(['modelable_type']);
			$migrate->index(['modelable_id', 'modelable_type']);
		}, function (Blueprint $table, Migrate $migrate){
			// Пример создания/удаления связи
//			$migrate->foreign('modelable_id', function ($foreign){
//				$foreign->references('id')->on('bsc_users')
//					->onUpdate('cascade')->onDelete('cascade');
//			});
//			$migrate->dropForeign('modelable_id');
		});
	}
}
