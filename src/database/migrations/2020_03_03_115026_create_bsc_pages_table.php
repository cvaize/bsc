<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use BSC\Database\Migrate;

class CreateBscPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::dropIfExists('pages');
    }
}
