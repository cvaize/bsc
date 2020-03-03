<?php

namespace BSC\App\Models\Pages;

use BSC\Database\Migrate;
use BSC\Database\MigrateInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
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
		(new Migrate('bsc_pages'))->createOrTable(function (Blueprint $table, Migrate $migrate){
			$migrate->createOrChange($table->bigIncrements('id'));
			$migrate->createOrChange($table->string('alias'));
			$migrate->createOrChange($table->string('path', 1000));
			$migrate->createOrChange($table->string('h1')->nullable());
			$migrate->createOrChange($table->unsignedBigInteger('modelable_id')->nullable());
			$migrate->createOrChange($table->string('modelable_type')->nullable());
			$migrate->createOrChange($table->string('meta_title')->nullable());
			$migrate->createOrChange($table->string('meta_description')->nullable());
			$migrate->createOrChange($table->string('meta_keywords')->nullable());
			$migrate->createOrChange($table->dateTime('published_at')->nullable());
			!Schema::hasColumn('bsc_pages', 'created_at') && $table->timestamps();
		});
	}
}
