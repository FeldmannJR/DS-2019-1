<template>
  <div class="indicatorNumeric" :style="{opacity: opacity}">
    <!-- Titulo do indicador -->
    <h2>{{text}}</h2>
    <!-- Valor do indicador -->
    <h1>{{value}}</h1>
  </div>
</template>
<script>
export default {
  props: ["indicator"],
  data() {
    const i = this.indicator;

    return {
      text: i.text,
      value: i.value,
      opacity: 0
    };
  },
  mounted() {
    var vm = this;
    setTimeout(function() {
      vm.opacity = 1;
    }, 50);
  }
};
</script>
