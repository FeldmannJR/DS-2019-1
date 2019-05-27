<?php

namespace App\Indicators;

use App\Enums\UpdateType;
use App\Indicators\Indicator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

abstract  class IndicatorSql extends Indicator
{

    protected function getHeConnection(){
        return ModelIndicators::getHeConnection();
    }

}
