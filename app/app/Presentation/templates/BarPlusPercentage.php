<?php


namespace App\Presentation\templates;


use App\Presentation\Slot;

class BarPlusPercentage extends Template
{

    public function getSlots(): array
    {
        return [
            Slot::$PERCENTAGE,
            Slot::$BAR,
            Slot::$PIE
        ];
    }

    public function getName(): string
    {
        return 'Bar And Graph';
    }
}