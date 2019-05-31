<?php

use Illuminate\Support\Facades\DB;
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
            $table->bigInteger('id')->nullable();
            $table->integer('indicator_id');
            $table->double('value');
            $table->timestamp('created_at')->useCurrent();
            $table->primary(['id', 'indicator_id']);
            // Adicionado relações
            $table->foreign('indicator_id')
                ->references('id')
                ->on('indicators');

        });

        Schema::create('indicators_history_unit', function (Blueprint $table) {
            $table->bigInteger('history_id');
            $table->integer('indicator_id');
            $table->integer('unit_id');
            $table->primary(['history_id', 'indicator_id']);
            // Adicionado relações
            $table->foreign(['history_id', 'indicator_id'])
                ->references(['id', 'indicator_id'])
                ->on('indicators_history');

        });

        // Criando a função do trigger
        DB::unprepared('
            CREATE OR REPLACE FUNCTION create_history_id_func() RETURNS TRIGGER as $create_history_id_func$
                DECLARE
                    total integer;
                BEGIN
                    select count(*) into total from indicators_history where indicator_id = NEW.indicator_id;
                    IF NEW.id IS NULL AND NEW.indicator_id IS NOT NULL THEN
                        NEW.id = total + 1;
                        return NEW;
                    end if;
                    RETURN NEW;
                END
            $create_history_id_func$ language "plpgsql";    
        ');
        // Criando o trigger pra aumentar o id baseado no indicador
        DB::unprepared('CREATE TRIGGER create_history_id BEFORE INSERT on indicators_history FOR EACH ROW EXECUTE PROCEDURE create_history_id_func();');


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP FUNCTION IF EXISTS create_history_id_func');
        DB::unprepared('DROP TRIGGER IF EXISTS create_history_id FROM indicators_history');
        Schema::dropIfExists('indicators_history_unit');
        Schema::dropIfExists('indicators_history');
        Schema::dropIfExists('indicators');


    }
}
