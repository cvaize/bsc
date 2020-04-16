<?php

namespace BSC\Database\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class UnsignedTinyInteger extends Type
{
	/**
	 * The name of the custom type.
	 *
	 * @var string
	 */
	const NAME = 'tinyinteger';

	/**
	 * Gets the SQL declaration snippet for a field of this type.
	 *
	 * @param mixed[] $fieldDeclaration The field declaration.
	 * @param AbstractPlatform $platform The currently used database platform.
	 *
	 * @return string
	 */
	public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
	{
		return 'TINYINT UNSIGNED';
	}

	/**
	 * Gets the name of this type.
	 *
	 * @return string
	 *
	 * @todo Needed?
	 */
	public function getName()
	{
		return self::NAME;
	}

}
