<?php

namespace App;

use App\Indicators\Indicator;
use App\Presentation\PresentationService;
use App\Presentation\SlideIndicator;
use App\Presentation\templates\Template;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    protected $guarded = [];

    public function indicators()
    {
        return $this->hasMany(SlideIndicator::class);
    }


    public function setSlot($slot, array $indicators)
    {
        $slots = count($this->getTemplate()->getSlots());
        if ($slot >= $slots) {
            return false;
        }
        if (count($indicators) != $slots) {
            return false;
        }

        $this->indicators()->delete();
        $slide_indicators = [];
        foreach ($indicators as $indicator) {
            $slide_indicators[] = SlideIndicator::make(['slot' => $slot, 'indicator_id' => $indicator->id]);
        }
        $this->indicators()->saveMany($slide_indicators);
        return true;
    }

    public function getTemplate(): Template
    {
        $service = resolve(PresentationService::class);
        return $service->getTemplates()[$this->template];
    }

    public function toArray()
    {
        $slots = [];
        $slide_indicators = $this->indicators;
        foreach ($slide_indicators as $ind) {
            $slot = $ind->slot;

            if (!array_key_exists($slot, $slots)) {
                $slots[$slot] = [];
            }
            $slots[$slot][] = $ind->indicator_id;

        }

        return [
            'id' => $this->id,
            'order' => $this->order,
            'template' => $this->template,
            'screen_time' => $this->screen_time,
            'slots' => $slots,
        ];
    }
}
