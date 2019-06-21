<template>
  <div class="indicatorNumeric" :style="{bottom: offset + 'vh', right: offset + 'vw'}">
    <Icon :icon="symbol"/>
    <div class="text">
      <h1 :style="{fontSize: h1Size + 'vh'}">{{value}}</h1>
      <h2 :style="{fontSize: h2Size + 'vh'}">{{title}}</h2>
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
    const size = this.multiple ? 0.5 : 1,
      i = this.indicator;

    return {
      title: i.title,
      symbol: i.symbol,
      value: i.value,
      h1Size: 35 * size,
      h2Size: 11 * size,
      offset: 5 * size
    };
  }
};
</script>
