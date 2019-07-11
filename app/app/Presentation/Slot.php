<?php


namespace App\Presentation;


class Slot implements \JsonSerializable
{

    public static $BAR, $PERCENTAGE, $PIE, $PLAIN;

    public static function registerSlots()
    {
        self::$BAR = new Slot('Bar', true);
        self::$PERCENTAGE = new Slot('Porcentagem', false);
        self::$PIE = new Slot('Pizza', true);
        self::$PLAIN = new Slot('Sem Formatação', false);

    }

    private $name;
    private $size;
    private $per_unit;

    /**
     * Slot constructor.
     * @param $name
     * @param $per_unit
     * @param int $size
     */
    public function __construct($name, $per_unit, $size = 1)
    {
        $this->name = $name;
        $this->per_unit = $per_unit;
        $this->size = $size;
    }


    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'name' => $this->name,
            'size' => $this->size,
            'per_unit' => $this->per_unit
        ];
    }
}