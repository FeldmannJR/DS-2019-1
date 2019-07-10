<template>
  <div class="indicatorMultiple" :style="{opacity: opacity}">
    <!-- Titulo do indicador -->
    <h4 class="text">{{text}}</h4>
    <div class="units">
      <div class="unit" v-for="unit in units" :key="unit">
        <h4 class="unitName">{{unit}}</h4>
        <h4 class="unitValue">{{data[units.indexOf(unit)]}}</h4>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: ["indicator"],
  data() {
    const i = this.indicator;

    return {
      text: i.text,
      data: i.data,
      units: i.units,
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
