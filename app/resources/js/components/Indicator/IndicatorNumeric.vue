<template>
  <div class="indicatorNumeric">
    <i icon class="material-icons">{{symbol}}</i>
    <div class="text">
      <h1>{{value}}</h1>
      <h2>{{title}}</h2>
    </div>
  </div>
</template>
<script>
export default {
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
