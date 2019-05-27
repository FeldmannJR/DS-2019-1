<?php

namespace App\Indicators;

use App\Enums\UpdateType;
use App\IndicatorHistory;
use App\Unit;
use Illuminate\Database\Eloquent\Model;
use ReflectionClass;

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
     * @var boolean se ele irá calcular o valor por unidade ou geral
     */
    private $per_unit = false;

    /**
     * Indicator constructor.
     * @param int|null $id
     * @param string $name
     * @param UpdateType $update_frequency
     * @param bool $per_unit
     */
    public function __construct(?int $id, string $name, UpdateType $update_frequency, bool $per_unit = False)
    {
        $this->id = $id;
        $this->name = $name;
        $this->update_frequency = $update_frequency;
        $this->per_unit = $per_unit;
    }


    /**
     * Calcula o indicador de uma unidade especifica se a unidade for null
     * ele calcula o geral
     * @return double|double[] valor do indicador calculado ou map por unidade onde a key é o id da unidade e o valor é o numero calculado
     */
    abstract public function calculateIndicator();


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isPerUnit(): bool
    {
        return $this->per_unit;
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
    public function getLastValue(Unit $unit = null): ?float
    {
        return ModelIndicators::getLastValue($this, $unit);
    }

    /**
     * @param Unit|null $unit unidade para ser calculado o valor
     * @return string|null valor a ser mostrado na view
     */
    public function getDisplayLastValue(Unit $unit = null): ?string
    {
        $value = $this->getLastValue($unit);
        if ($value === null) {
            return 'Sem entrada';
        } else {
            return number_format($value, 2);
        }


    }

    /**
     * Calcula o valor do indicador e salva no banco este valor
     * @return void
     */
    public function calculateAndSave()
    {
        // Chama a  função nas subclasses
        $value = $this->calculateIndicator();
        if ($value === null) {
            return;
        }
        // Verifica se esse indicador possui valores por unidade
        if ($this->per_unit) {
            $allUnits = Unit::getAllUnits();
            // Percorre todas as unidades que foram calculadas
            foreach ($value as $unit_id => $unit_value) {
                // Verifica se a chave é um inteiro e o valor um numerico, e verifica se a unidade existe no nosso banco
                if (is_int($unit_id) && is_numeric($unit_value) && array_key_exists($unit_id, $allUnits)) {
                    echo "Calculated $this->name in unit $unit_id to $unit_value<br>";
                    // Adiciona o valor ao banco
                    ModelIndicators::addIndicatorHistoryValue($this->getId(), $unit_value, $allUnits[$unit_id]);
                }
            }

        } else {
            if (is_numeric($value)) {
                echo "Calculated $this->name to $value<br>";
                ModelIndicators::addIndicatorHistoryValue($this->getId(), $value);
            }
        }
    }

    public function getClassName()
    {
        $ref = new ReflectionClass($this);
        return $ref->getName();

    }
}

?>

