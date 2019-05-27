<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IndicatorHistoryUnit extends Model
{
    protected $table = 'indicators_history_unit';
    //

    public function unit(){
        return $this->hasOne('App\Unit');
    }

    public function indicatorHistory(){
        return $this->belongsTo('App\IndicatorHistory');
    }
}
