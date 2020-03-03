<?php


namespace BSC\Database;


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Facades\Schema;

//use Illuminate\Database\Schema\Blueprint;

class Migrate
{
//	public static $methods = [
//
//	];
	/**
	 * @var string
	 */
	public $table;

	public function __construct(string $table) {
		$this->table = $table;
	}


	public function createOrTable($cb)
	{
		/**
		 * @var Migrate $that
		 */
		$that = $this;
		if (Schema::hasTable($this->table)) {
			Schema::table($this->table, function (Blueprint $table) use ($cb, $that){
				$cb($table, $that);
			});
		} else {
			Schema::create($this->table, function (Blueprint $table) use ($cb, $that){
				$cb($table, $that);
			});
		}
	}

	public function createOrChange(ColumnDefinition $column): ColumnDefinition
	{
		if (Schema::hasColumn($this->table, $column->get('name'))) {
			$column->change();
		}
		return $column;
	}
//
//	public static function createOrChange1($ar1, $ar2){
//
//	}
}
