<?php

namespace BSC\App\Models;

use Illuminate\Database\Eloquent\Model as DefaultModel;
use Illuminate\Support\Str;


class Model extends DefaultModel
{
	protected function checkUnique(string $column, string $alias): bool
	{
		return 0 === $this->where($column, $alias)->count();
	}

	protected function getUnique(string $column, string $alias): string
	{
		$aliasOrig = Str::slug(Str::lower(trim($alias)), '-', 'en');
		$alias     = $aliasOrig;
		$i         = 1;
		while ($this->checkUnique($column, $alias) !== true) {
			$alias = $aliasOrig . '-' . $i;
			$i++;
		}
		return $alias;
	}

	protected function setUnique(string $column, string $alias): void
	{
		$alias = $this->getUnique($column, $alias);
		$currentAlias = $this->{$column}??'';

		if ($alias !== $currentAlias) {
			$this->{$column} = $alias;
			$this->save();
		}
	}
}
