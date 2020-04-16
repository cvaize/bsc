<?php


namespace BSC\Database;


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Migrate
{
//	public static $methods = [
//
//	];
	/**
	 * @var string
	 */
	public $tableName;

	/**
	 * @var Blueprint
	 */
	public $table;

	/**
	 * @var string
	 */
	public $prefix;

	public function __construct(string $tableName)
	{
		$this->tableName = $tableName;
		$this->prefix = \DB::getTablePrefix();
	}


	public function createOrTable($cb, ...$cbs)
	{
		/**
		 * @var Migrate $that
		 */
		$that = $this;
		if (Schema::hasTable($this->tableName)) {
			Schema::table($this->tableName, function (Blueprint $table) use ($cb, $that) {
				$this->table = $table;
				$cb($table, $that);
			});
		} else {
			Schema::create($this->tableName, function (Blueprint $table) use ($cb, $that) {
				$this->table = $table;
				$cb($table, $that);
			});
		}

		foreach ($cbs as $cb){
			Schema::table($this->tableName, function (Blueprint $table) use ($cb, $that) {
				$this->table = $table;
				$cb($table, $that);
			});
		}
	}

	public function createOrChange(ColumnDefinition $column): ColumnDefinition
	{
		dump(Schema::hasColumn($this->tableName, $column->get('name')), $this->tableName, $column->get('name'));
		if (Schema::hasColumn($this->tableName, $column->get('name'))) {
			$column->change();
		}
		return $column;
	}

	protected function createIndexName($type, array $columns)
	{
		$index = strtolower($this->prefix . $this->tableName . '_' . implode('_', $columns) . '_' . $type);

		return str_replace(['-', '.'], '_', $index);
	}

	public function getIndexes():Collection
	{
		return collect(DB::select('SHOW INDEXES FROM '.$this->prefix . $this->tableName))->pluck('Key_name');
	}

	public function getRelationships():Collection
	{
		// https://stackoverflow.com/questions/20855065/how-to-find-all-the-relations-between-all-mysql-tables
		return collect(DB::select('SELECT 
  `CONSTRAINT_NAME`,                      	-- Relationship name
  --`TABLE_SCHEMA`,                          -- Foreign key schema
  --`TABLE_NAME`,                            -- Foreign key table
  --`COLUMN_NAME`,                           -- Foreign key column
  --`REFERENCED_TABLE_SCHEMA`,               -- Origin key schema
  --`REFERENCED_TABLE_NAME`,                 -- Origin key table
  --`REFERENCED_COLUMN_NAME`                 -- Origin key column
FROM
  `INFORMATION_SCHEMA`.`KEY_COLUMN_USAGE`  -- Will fail if user don\'t have privilege
WHERE
	`TABLE_NAME` = "'.$this->prefix . $this->tableName.'"
  AND `TABLE_SCHEMA` = SCHEMA()                -- Detect current schema in USE 
  AND `REFERENCED_TABLE_NAME` IS NOT NULL;'))->pluck('CONSTRAINT_NAME');
	}

	public function hasIndex(string $name){
		return $this->getIndexes()->contains($name);
	}

	public function hasRelationships(string $name){
		return $this->getRelationships()->contains($name);
	}

	public function hasColumn(string $column){
		return Schema::hasColumn($this->tableName, $column);
	}

	public function getUniqueIndexName(array $columns){
		return $this->createIndexName('unique', $columns);
	}

	public function getIndexName(array $columns){
		return $this->createIndexName('index', $columns);
	}

	public function getForeignName(array $columns){
		return $this->createIndexName('foreign', $columns);
	}

	public function unique(array $columns){
		$name = $this->getUniqueIndexName($columns);
		if(!$this->hasIndex($name)){
			$this->table->unique($columns, $name);
		}
	}

	public function dropUnique(array $columns){
		$name = $this->getUniqueIndexName($columns);
		if($this->hasIndex($name)){
			$this->table->dropUnique($name);
		}
	}

	public function index(array $columns){
		$name = $this->getIndexName($columns);
		if(!$this->hasIndex($name)){
			$this->table->index($columns, $name);
		}
	}

	public function dropIndex(array $columns){
		$name = $this->getIndexName($columns);
		if($this->hasIndex($name)){
			$this->table->dropIndex($name);
		}
	}

	public function foreign(string $column, $cb){
		$name = $this->getForeignName([$column]);
		if(!$this->hasRelationships($name)){
			$cb($this->table->foreign($column, $name));
		}
	}

	public function dropForeign(string $column){
		$name = $this->getForeignName([$column]);
		if($this->hasRelationships($name)){
			$this->table->dropForeign($name);
		}
	}
}
