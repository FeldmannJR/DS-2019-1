<template>
  <div class="indicatorNumeric">
    <!-- Simbolo atribuido ao indicador -->
    <i icon class="material-icons">{{symbol}}</i>
    <div class="text">
      <!-- Valor do indicador -->
      <h1>{{value}}</h1>
      <!-- Titulo do indicador -->
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
