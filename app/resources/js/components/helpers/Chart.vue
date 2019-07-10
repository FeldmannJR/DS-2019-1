<template>
  <canvas :id="id" :graph="graph"></canvas>
</template>
<script>
export default {
  // Helper para criar charts utilizando Chart.js

  props: ["id", "graph", "labels", "datasets", "options"],
  data() {
    return {
      chart: null
    };
  },
  mounted() {
    // Renderiza o chart no canvas indicado pelo id passado
    this.chart = new Chart(this.ctx, this.chartStructure);
  },
  computed: {
    ctx() {
      return document.getElementById(this.id).getContext("2d");
    },
    chartStructure() {
      return {
        // Tipo de grafico
        type: this.graph || "bar",

        data: {
          // Legendas
          labels: this.labels,
          // Valores
          datasets: this.datasets
        },

        // Configuracoes opcionais
        options: this.options
      };
    }
  },
  updated() {
    this.chart = new Chart(this.ctx, this.chartStructure);
  }
};
</script>
