<?php

namespace App\Models\Pages;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;


class Page extends Model
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
}
