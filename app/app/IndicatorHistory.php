<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IndicatorHistory extends Model
{
    protected $table = "indicators_history";


    public function unit()
    {
        return $this->hasOne('App\IndicatorHistoryUnit','indicator_history_id');
    }
}
