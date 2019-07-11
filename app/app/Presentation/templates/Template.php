<?php


namespace App\Presentation\templates;


abstract class Template implements \JsonSerializable
{

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