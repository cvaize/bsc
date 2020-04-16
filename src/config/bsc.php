<?php

return [

	/**
	 * Для использования создайте директорию resources/views/themes/{theme} и укажите переменную
	 * @var string
	 */
	'theme' => 'custom',

	'aliases' => [
		'BscStaticController'=>'BSC\\App\\Http\\Controllers\\StaticController',
		'BscUser'=>'BSC\\App\\Models\\Users\\User',
		'BscCurrency'=>'BSC\\App\\Models\\Currencies\\Currency',
		'BscManufacturer'=>'BSC\\App\\Models\\Manufacturers\\Manufacturer',
		'BscCategory'=>'BSC\\App\\Models\\Categories\\Category',
		'BscProduct'=>'BSC\\App\\Models\\Products\\Product',
		'BscProductsToCategories'=>'BSC\\App\\Models\\Products\\ProductsToCategories',
		'BscAttrGroup'=>'BSC\\App\\Models\\Attrs\\AttrGroup',
		'BscAttr'=>'BSC\\App\\Models\\Attrs\\Attr',
		'AttrValue'=>'BSC\\App\\Models\\Attrs\\AttrValue',
	],

];
