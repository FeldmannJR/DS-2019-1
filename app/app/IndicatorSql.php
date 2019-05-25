<?php

namespace App;

use App\Enums\UpdateType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

abstract  class IndicatorSql extends Indicator
{

    protected function getHeConnection(){
        return DB::connection("pgsql_agh");
    }

}
