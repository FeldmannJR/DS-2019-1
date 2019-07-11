<?php


namespace App\Presentation\templates;


use App\Presentation\Slot;

class Full extends Template
{

    public function getSlots(): array
    {
        return [
            Slot::$PERCENTAGE
        ];
    }

    public function getName(): string
    {
        return 'Fullscreen';
    }
}