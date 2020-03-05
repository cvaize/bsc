<?php

namespace BSC\App\Models\Pages;

use BSC\Database\Migrate;
use BSC\Database\MigrateInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;


/**
 * BSC\App\Models\Pages\Page
 *
 * @property int $id
 * @property string $alias
 * @property string $path
 * @property string|null $h1
 * @property int|null $modelable_id
 * @property string|null $modelable_type
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $meta_keywords
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\BSC\App\Models\Pages\Page filter($frd)
 * @method static \Illuminate\Database\Eloquent\Builder|\BSC\App\Models\Pages\Page newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\BSC\App\Models\Pages\Page newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\BSC\App\Models\Pages\Page query()
 * @method static \Illuminate\Database\Eloquent\Builder|\BSC\App\Models\Pages\Page whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\BSC\App\Models\Pages\Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\BSC\App\Models\Pages\Page whereH1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\BSC\App\Models\Pages\Page whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\BSC\App\Models\Pages\Page whereMetaDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\BSC\App\Models\Pages\Page whereMetaKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\BSC\App\Models\Pages\Page whereMetaTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\BSC\App\Models\Pages\Page whereModelable1Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\BSC\App\Models\Pages\Page whereModelable2Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\BSC\App\Models\Pages\Page whereModelableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\BSC\App\Models\Pages\Page whereModelableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\BSC\App\Models\Pages\Page wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\BSC\App\Models\Pages\Page wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\BSC\App\Models\Pages\Page whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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

	public function migrate(){
		(new Migrate($this->getTable()))->createOrTable(function (Blueprint $table, Migrate $migrate){
			// Тут только создание колонок, никаких индексов и связей
			// Создание тут связей/индексов повлечет за собой ошибку
			$migrate->createOrChange($table->bigIncrements('id'));
			$migrate->createOrChange($table->string('alias'));
			$migrate->createOrChange($table->string('path'));
			$migrate->createOrChange($table->string('h1')->nullable());
			$migrate->createOrChange($table->unsignedBigInteger('modelable_id')->nullable());
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

	/**
	 * @return string
	 */
	public function getAlias(): string
	{
		return $this->alias;
	}

	/**
	 * @param string $alias
	 */
	public function setAlias(string $alias)
	{
		$this->alias = $alias;
	}

	/**
	 * @return string
	 */
	public function getPath(): string
	{
		return $this->path;
	}

	/**
	 * @param string $path
	 */
	public function setPath(string $path)
	{
		$this->path = $path;
	}

	/**
	 * @return string|null
	 */
	public function getH1(): string
	{
		return $this->h1;
	}

	/**
	 * @param string|null $h1
	 */
	public function setH1(string $h1)
	{
		$this->h1 = $h1;
	}

	/**
	 * @return int|null
	 */
	public function getModelableId(): int
	{
		return $this->modelable_id;
	}

	/**
	 * @param int|null $modelable_id
	 */
	public function setModelableId(int $modelable_id)
	{
		$this->modelable_id = $modelable_id;
	}

	/**
	 * @return string|null
	 */
	public function getModelableType(): string
	{
		return $this->modelable_type;
	}

	/**
	 * @param string|null $modelable_type
	 */
	public function setModelableType(string $modelable_type)
	{
		$this->modelable_type = $modelable_type;
	}

	/**
	 * @return string|null
	 */
	public function getMetaTitle(): string
	{
		return $this->meta_title;
	}

	/**
	 * @param string|null $meta_title
	 */
	public function setMetaTitle(string $meta_title)
	{
		$this->meta_title = $meta_title;
	}

	/**
	 * @return string|null
	 */
	public function getMetaDescription(): string
	{
		return $this->meta_description;
	}

	/**
	 * @param string|null $meta_description
	 */
	public function setMetaDescription(string $meta_description)
	{
		$this->meta_description = $meta_description;
	}

	/**
	 * @return string|null
	 */
	public function getMetaKeywords(): string
	{
		return $this->meta_keywords;
	}

	/**
	 * @param string|null $meta_keywords
	 */
	public function setMetaKeywords(string $meta_keywords)
	{
		$this->meta_keywords = $meta_keywords;
	}

	/**
	 * @return \Illuminate\Support\Carbon|null
	 */
	public function getPublishedAt(): Carbon
	{
		return $this->published_at;
	}

	/**
	 * @param \Illuminate\Support\Carbon|null $published_at
	 */
	public function setPublishedAt(\Illuminate\Support\Carbon $published_at = null)
	{
		$this->published_at = $published_at;
	}


}
