<?php


namespace App\Presentation\templates;


use App\Presentation\Slot;
use App\Presentation\Slide;

abstract class Template implements \JsonSerializable
{

    /**
     * @return Slot[]
     */
    public abstract function getSlots(): array;

    public abstract function getName(): string;

    public function jsonSerialize()
    {
        return [
            'slots' => $this->getSlots(),
            'name' => $this->getName()
        ];
    }
}