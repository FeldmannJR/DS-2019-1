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
            $table->boolean('per_unit')->default(false);
            $table->string('class')->nullable(false);
            $table->unique('name');
            $table->timestamps();
        });
        // Criando Tabela indicators_history
        Schema::create('indicators_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('indicator_id');
            $table->double('value');
            $table->timestamp('created_at')->default(now());
            // Adicionado relações
            $table->foreign('indicator_id')
                ->references('id')
                ->on('indicators');

        });


        Schema::create('indicators_history_unit', function (Blueprint $table) {
            $table->bigInteger('indicator_history_id');
            $table->integer('unit_id');
            // Adicionado relações
            $table->foreign(['indicator_history_id'])
                ->references(['id'])
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
