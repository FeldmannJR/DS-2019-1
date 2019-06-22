<template>
  <div class="panel">
    <div v-for="row in indicators" class="row" :key="indicators.indexOf(row)">
      <div v-for="indicator in row" class="frame" :key="row.indexOf(indicator)">
        <IndicatorNumeric v-if="indicator.type === 'numeric'" :indicator="indicator"/>
        <IndicatorStatistic v-else :indicator="indicator" :stretched="row.length == 1 && multiple"/>
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
  },
  mounted() {
    this.setSize("h1", 35);
    this.setSize("h2", 10);
    this.setSize("h3", 4);
    this.setSize("[label]", 5, "vh", "width");
    this.setSize("[label]", 5, "vh", "height");
    this.setSize(".indicatorNumeric", 5, "vh", "bottom");
    this.setSize(".indicatorNumeric", 5, "vw", "right");
  },
  methods: {
    setSize(selector, size, metric = "vh", attribute = "fontSize", vm = this) {
      document.querySelectorAll(selector).forEach(el => {
        const ratio = this.multiple ? 0.5 : 1;
        el.style[attribute] = size * ratio + metric;
      });
    }
  }
};
</script>