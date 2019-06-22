<template>
  <canvas :id="id"></canvas>
</template>
<script>
export default {
  // Helper para criar charts utilizando Chart.js

  props: ["id", "type", "labels", "datasets", "options"],
  mounted() {
    // Renderiza o chart no canvas indicado pelo id passado
    var ctx = document.getElementById(this.id).getContext("2d");
    var chart = new Chart(ctx, {
      // Tipo de grafico
      type: this.type || "bar",

      data: {
        // Legendas
        labels: this.labels,
        // Valores
        datasets: this.datasets
      },

      // Configuracoes opcionais
      options: this.options
    });
  }
};
</script>
