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
		'BscProductCategory'=> 'BSC\\App\\Models\\Products\\ProductCategory',
		'BscAttrGroup'=>'BSC\\App\\Models\\Attrs\\AttrGroup',
		'BscAttr'=>'BSC\\App\\Models\\Attrs\\Attr',
		'BscAttrValue'=>'BSC\\App\\Models\\Attrs\\AttrValue',
		'BscAttrCategory'=>'BSC\\App\\Models\\Attrs\\AttrCategory',
		'BscProductAttr'=>'BSC\\App\\Models\\Products\\ProductAttr',
		'BscProductAttrValue'=>'BSC\\App\\Models\\Products\\ProductAttrValue',
	],

];
