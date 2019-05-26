<?php

namespace App\Indicators;

use App\Enums\UpdateType;
use App\IndicatorHistory;
use App\Unit;
use Illuminate\Database\Eloquent\Model;

abstract class Indicator
{


    /**
     * @var integer|null id no banco de dados
     */
    private $id;
    /**
     * @var string nome para display
     */
    private $name;
    /**
     * @var UpdateType qual o tipo de update este indicador possuí
     */
    private $update_frequency;

    /**
     * Indicator constructor.
     * @param int|null $id
     * @param string $name
     * @param UpdateType $update_frequency
     */
    public function __construct(?int $id, string $name, UpdateType $update_frequency)
    {
        $this->id = $id;
        $this->name = $name;
        $this->update_frequency = $update_frequency;
    }


    /**
     * Calcula o indicador de uma unidade especifica se a unidade for null
     * ele calcula o geral
     * @param Unit $unit
     * @return double valor do indicador calculado
     */
    abstract public function calculateIndicator(Unit $unit = null);


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return UpdateType
     */
    public function getUpdateFrequency(): UpdateType
    {
        return $this->update_frequency;
    }

    /**
     * @param Unit|null $unit de qual unidade irá buscar o valor, caso seja null vai pegar o geral
     * @return double|null ultimo valor calculado
     */
    public function getLastValue(Unit $unit = null)
    {
        return ModelIndicators::getLastValue($this, $unit);
    }
}

?>

