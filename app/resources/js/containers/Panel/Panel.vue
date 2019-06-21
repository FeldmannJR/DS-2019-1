<template>
  <div class="panel">
    <div v-for="row in indicators" class="row">
      <div v-for="indicator in row" class="frame">
        <IndicatorNumeric
          v-if="indicator.type === 'numeric'"
          :indicator="indicator"
          :multiple="multiple"
        />
        <IndicatorStatistic v-else :indicator="indicator" :multiple="multiple"/>
      </div>
    </div>
  </div>
</template>

<script>
require("./Panel.scss");
import IndicatorNumeric from "../../components/Indicator/IndicatorNumeric";
import IndicatorStatistic from "../../components/Indicator/IndicatorStatistic";
Chart.defaults.global.legend.display = false;

export default {
  components: {
    IndicatorNumeric,
    IndicatorStatistic
  },
  props: ["indicators"],
  data() {
    return {
      multiple: this.indicators.flat().length > 1
    };
  }
};
</script>