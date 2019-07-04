<template>
  <div class="indicatorNumeric">
    <div class="text" :style="{opacity: opacity}">
      <!-- Titulo do indicador -->
      <h2>{{title}}</h2>
      <!-- Valor do indicador -->
      <h1>{{value}}</h1>
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
