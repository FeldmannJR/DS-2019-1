<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndicatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indicators', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('update_type');
            $table->unique('name');
            $table->timestamps();
        });
        // Criando Tabela indicators_history
        Schema::create('indicators_history', function (Blueprint $table) {
            $table->unsignedBigInteger('id');
            $table->integer('indicator_id');
            $table->double('value');
            $table->primary(["id","indicator_id"]);
            $table->timestamp('created_at')->default(now());
            // Adicionado relações
            $table->foreign('indicator_id')
                ->references('id')
                ->on('indicators');

        });
        /* Adicionado o auto increment
         * Unico jeito que achei na net
         * https://github.com/laravel/framework/issues/17204
         */
        Schema::table('indicators_history',function (Blueprint $table){
            $table->integer('id',true,true)->change();
        });

        Schema::create('indicators_history_unit', function (Blueprint $table) {
            $table->bigInteger('indicator_history_id');
            $table->integer('indicator_id');
            $table->integer('unit_id');
            // Adicionado relações
            $table->foreign(['indicator_history_id','indicator_id'])
                ->references(['id','indicator_id'])
                ->on('indicators_history');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('indicators_history_unit');
        Schema::dropIfExists('indicators_history');
        Schema::dropIfExists('indicators');


    }
}
