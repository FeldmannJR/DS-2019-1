<template>
  <div class="slider">
    <!-- Painel deve receber de 1-4 indicadores divididos em 2 arrays -->
    <div v-for="row in indicators" class="row" :key="indicators.indexOf(row)">
      <div v-for="indicator in row" class="frame" :key="row.indexOf(indicator)">
        <!-- Renderiza indicador numerico -->
        <IndicatorNumeric
          v-if="indicator.type === 'numeric'"
          :indicator="indicator"
          :key="indicator.title"
        />
        <!-- Renderiza indicador estatistico -->
        <IndicatorStatistic
          v-else
          :indicator="indicator"
          :stretched="row.length == 1 && multiple"
          :key="indicator.title"
        />
      </div>
    </div>
  </div>
</template>
<script>
import IndicatorNumeric from "./IndicatorNumeric";
import IndicatorStatistic from "./IndicatorStatistic";

export default {
  props: ["indicators"],
  components: {
    IndicatorNumeric,
    IndicatorStatistic
  },
  methods: {
    // Ajusta tamanhos de headers, icones, legendas e posicionamento de indicadores numericos
    setSizes() {
      this.setSize(".slider h1", 40);
      this.setSize(".slider h2", 10);
      this.setSize(".slider h3", 4);
      this.setSize(".slider [icon]", 70);
      this.setSize(".slider [label]", 5, "vh", "width");
      this.setSize(".slider [label]", 5, "vh", "height");
      this.setSize(".slider .indicatorNumeric", 5, "vh", "bottom");
      this.setSize(".slider .indicatorNumeric", 5, "vw", "right");
    },
    // Altera um atributo CSS, utilizando metade do valor passado se multiple for verdadeiro
    setSize(selector, size, metric = "vh", attribute = "fontSize", vm = this) {
      document.querySelectorAll(selector).forEach(el => {
        const ratio = this.multiple ? 0.5 : 1;
        el.style[attribute] = size * ratio + metric;
      });
    }
  },
  computed: {
    // Indica se ha mais de um indicador no painel
    multiple() {
      return this.indicators.flat().length > 1;
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
