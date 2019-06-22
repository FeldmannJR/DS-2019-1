<template>
  <div class="indicatorNumeric">
    <Icon :icon="symbol"/>
    <div class="text">
      <h1>{{value}}</h1>
      <h2>{{title}}</h2>
    </div>
  </div>
</template>
<script>
require("./IndicatorNumeric.scss");
import Icon from "../helpers/Icon";

export default {
  components: {
    Icon
  },
  props: ["indicator", "multiple"],
  data() {
    const i = this.indicator;

    return {
      title: i.title,
      symbol: i.symbol,
      value: i.value
    };
  }
};
</script>
