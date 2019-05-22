<?php
    namespace app;
    use App\Enums\UpdateType;

    abstract class Indicator {
        


        /**
         * @var UpdateType
         */
        private $updateType;


        private $lastValue = [];

        /**
         * Indicator constructor.
         * @param UpdateType $updateType
         */
        public function __construct(UpdateType $updateType)
        {
            $this->updateType = $updateType;
        }




        abstract public function calculateIndicator($unidade = null);



    }

?>