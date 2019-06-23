<template>
  <div class="panel">
    <!-- Painel deve receber de 1-4 indicadores divididos em 2 arrays -->
    <div v-for="row in indicators" class="row" :key="indicators.indexOf(row)">
      <div v-for="indicator in row" class="frame" :key="row.indexOf(indicator)">
        <!-- Renderiza indicador numerico -->
        <IndicatorNumeric v-if="indicator.type === 'numeric'" :indicator="indicator" :key="indicator.title"/>
        <!-- Renderiza indicador estatistico -->
        <IndicatorStatistic v-else :indicator="indicator" :stretched="row.length == 1 && multiple" :key="indicator.title"/>
      </div>
    </div>
  </div>
</template>

<script>
require("./Panel.scss");
import IndicatorNumeric from "../../components/Indicator/IndicatorNumeric";
import IndicatorStatistic from "../../components/Indicator/IndicatorStatistic";

export default {
  components: {
    IndicatorNumeric,
    IndicatorStatistic
  },
  props: ["slides", "timers"],
  data() {
    return {
      // Index do slide atual
      index: 0
    };
  },
  computed: {
    // Indica se ha mais de um indicador no painel
    multiple() {
      return this.indicators.flat().length > 1;
    },
    // Indicadores do slide atual
    indicators() {
      // Inicializa timer para avançar para o próximo slide
      setTimeout(this.changeSlide, this.timers[this.index] * 1000);
      return this.slides[this.index];
    }
  },
  methods: {
    // Avança 1 slide
    changeSlide() {
      this.index = (this.index + 1) % this.slides.length;
    },
    // Ajusta tamanhos de headers, icones, legendas e posicionamento de indicadores numericos
    setSizes() {
      this.setSize("h1", 35);
      this.setSize("h2", 10);
      this.setSize("h3", 4);
      this.setSize("[icon]", 70);
      this.setSize("[label]", 5, "vh", "width");
      this.setSize("[label]", 5, "vh", "height");
      this.setSize(".indicatorNumeric", 5, "vh", "bottom");
      this.setSize(".indicatorNumeric", 5, "vw", "right");
    },
    // Altera um atributo CSS, utilizando metade do valor passado se multiple for verdadeiro
    setSize(selector, size, metric = "vh", attribute = "fontSize", vm = this) {
      document.querySelectorAll(selector).forEach(el => {
        const ratio = this.multiple ? 0.5 : 1;
        el.style[attribute] = size * ratio + metric;
      });
    }
  },
  mounted() {
    this.setSizes();
  },
  updated() {
    this.setSizes();
  }
};
</script>