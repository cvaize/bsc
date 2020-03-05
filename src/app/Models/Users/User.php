<?php

namespace BSC\App\Models\Users;

use BSC\Database\Migrate;
use BSC\Database\MigrateInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;


/**
 * BSC\App\Models\Users\User
 *
 * @property int $id
 * @property string $unique_name
 * @property string $f_name
 * @property string $l_name
 * @property string $m_name
 * @property string $email
 * @property string $phone
 * @property int $payment_id
 * @property int $shipping_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\BSC\App\Models\Users\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\BSC\App\Models\Users\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\BSC\App\Models\Users\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\BSC\App\Models\Users\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\BSC\App\Models\Users\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\BSC\App\Models\Users\User whereFName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\BSC\App\Models\Users\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\BSC\App\Models\Users\User whereLName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\BSC\App\Models\Users\User whereMName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\BSC\App\Models\Users\User wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\BSC\App\Models\Users\User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\BSC\App\Models\Users\User whereShippingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\BSC\App\Models\Users\User whereUniqueName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\BSC\App\Models\Users\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Model implements MigrateInterface
{
	protected $table = 'bsc_users';

	protected $fillable = [
		'unique_name',
		'f_name',
		'l_name',
		'm_name',
		'email',
		'phone',
		'payment_id',
		'shipping_id',

//		'full_address', // лучше вынести адреса в отдельную таблицу, так как у пользователя может быть множество адресов
//		'street',
//		'street_nr',
//		'home',
//		'apartment',
//		'zip',
//		'city',
//		'state',
//		'country',
//		'lang', // лучше на клиенте определять язык, если человек в первый раз зашел на сайт
	];

	public function migrate(){
		(new Migrate($this->getTable()))->createOrTable(function (Blueprint $table, Migrate $migrate){
			// Тут только создание колонок, никаких индексов и связей
			// Создание тут связей/индексов повлечет за собой ошибку
			$migrate->createOrChange($table->bigIncrements('id'));
			$migrate->createOrChange($table->string('unique_name'));
			$migrate->createOrChange($table->string('f_name'));
			$migrate->createOrChange($table->string('l_name'));
			$migrate->createOrChange($table->string('m_name'));
			$migrate->createOrChange($table->string('email'));
			$migrate->createOrChange($table->string('phone', 24));
			$migrate->createOrChange($table->unsignedTinyInteger('payment_id'));
			$migrate->createOrChange($table->unsignedTinyInteger('shipping_id'));
			!$migrate->hasColumn('created_at') && $table->timestamps();
		}, function (Blueprint $table, Migrate $migrate){
			$migrate->unique(['unique_name']);
			$migrate->index(['email']);
			$migrate->index(['phone']);
		});
	}
}
