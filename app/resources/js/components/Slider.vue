<template>
  <div class="slider">
    <!-- Painel deve receber de 1-4 indicadores divididos em 2 arrays -->
    <div v-for="row in indicators" class="row" :key="indicators.indexOf(row)">
      <div v-for="indicator in row" class="frame" :key="row.indexOf(indicator)">
        <IndicatorPanel
          :indicator="indicator"
          :multiple="multiple"
          :stretched="row.length == 1 && multiple"
        />
      </div>
    </div>
  </div>
</template>
<script>
import IndicatorPanel from "./IndicatorPanel";

export default {
  props: ["indicators"],
  components: {
    IndicatorPanel
  },
  methods: {
    // Ajusta tamanhos de headers, icones, legendas e posicionamento de indicadores numericos
    setSizes() {
      this.setSize(".slider h1", 40);
      this.setSize(".slider h2", 10);
      this.setSize(".slider h3", 4);
      this.setSize(".slider [label]", 5, "vh", "width");
      this.setSize(".slider [label]", 5, "vh", "height");
    },
    // Altera um atributo CSS, utilizando metade do valor passado se multiple for verdadeiro
    setSize(selector, size, metric = "vh", attribute = "fontSize", vm = this) {
      const ratio = this.multiple ? 0.5 : 1;
      document.querySelectorAll(selector).forEach(el => {
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
