<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\IndicatorHistory
 *
 * @property-read \App\IndicatorHistoryUnit $unit
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IndicatorHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IndicatorHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IndicatorHistory query()
 * @mixin \Eloquent
 */
class IndicatorHistory extends Model
{
    protected $table = "indicators_history";


    public function unit()
    {
        return $this->hasOne('App\IndicatorHistoryUnit','indicator_history_id');
    }
}
