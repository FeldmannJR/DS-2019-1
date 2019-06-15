<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\IndicatorHistoryUnit
 *
 * @property-read \App\IndicatorHistory $indicatorHistory
 * @property-read \App\Unit $unit
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IndicatorHistoryUnit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IndicatorHistoryUnit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\IndicatorHistoryUnit query()
 * @mixin \Eloquent
 */
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
