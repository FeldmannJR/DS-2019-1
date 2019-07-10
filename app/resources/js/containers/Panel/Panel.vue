<template>
  <div class="panel">
    <div class="fixed">
      <IndicatorFixed
        v-for="indicator in fixed"
        :indicator="indicator"
        :key="fixed.indexOf(indicator)"
      />
    </div>
    <Slider :key="index" :indicators="slides[index]" />
  </div>
</template>

<script>
require("./Panel.scss");
import IndicatorFixed from "../../components/IndicatorFixed";
import Slider from "../../components/Slider";

export default {
  props: ["fixed", "slides", "timers"],
  components: {
    IndicatorFixed,
    Slider
  },
  data() {
    return {
      // Index do slide atual
      index: 0
    };
  },
  methods: {
    // Avança 1 slide
    changeSlide() {
      this.index = (this.index + 1) % this.slides.length;
      setTimeout(this.changeSlide, this.timers[this.index] * 1000);
    }
  },
  mounted() {
    setTimeout(this.changeSlide, this.timers[this.index] * 1000);
  }
};
</script>