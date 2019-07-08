<template>
  <div class="indicatorStatistic" :stretched="stretched">
    <!-- Titulo do indicador -->
    <h2>{{title}}</h2>
    <div class="chart">
      <!-- Grafico que representa os valores -->
      <Chart :id="title" :type="graph" :datasets="datasets" :options="options" />
    </div>
    <!-- Legenda -->
    <div class="legend">
      <div class="label" v-for="label in labels" :key="label">
        <!-- Circulo com cor respectiva ao valor no grafico -->
        <div label :style="{ backgroundColor: colors[labels.indexOf(label)] }" />
        <!-- Rotulo -->
        <h3>{{label}}</h3>
      </div>
    </div>
  </div>
</template>
<script>
import Chart from "./helpers/Chart";

export default {
  components: {
    Chart
  },
  props: ["indicator", "stretched"],
  data() {
    const i = this.indicator;

    // Configuracoes opcionais do chart
    var options = {
      // Oculta legenda nativa do chart
      legend: {
        display: false
      },
      plugins: {
        // Plugin datalabels utilizado para exibir os valores em cima dos graficos
        datalabels: {
          color: "white",
          textShadowColor: "black",
          textShadowBlur: 4,
          font: {
            size: window.innerWidth / 30
          }
        }
      },
      elements: {
        arc: {
          borderWidth: 0
        }
      },
      scales: {}
    };

    // Ajusta intervalo de valores para graficos dos tipos barra e linha
    if (this.indicator.graph === "bar") {
      const maxValue = Math.max(...i.data);

      options.scales.yAxes = [
        {
          ticks: {
            suggestedMin: 0,
            max: maxValue * 1.5
          }
        }
      ];
    }

    return {
      title: i.title,
      graph: i.graph,
      labels: i.units,
      options: options,
      // Esquema padrao de cores para diferenciar os dados
      colors: ["#344669", "#3C8376", "#3ec940", "#dde02c", "#dd9c2c", "#af3838"]
    };
  },
  computed: {
    // Cria dataset com dados e esquema de cores
    datasets() {
      const data = this.indicator.data;

      if (this.indicator.graph === "bar") {
        return data.map((d, i) => {
          return { data: [d], backgroundColor: this.colors[i] };
        });
      } else {
        return [
          {
            data: data,
            backgroundColor: data.map((d, i) => {
              return this.colors[i];
            })
          }
        ];
      }
    }
  }
};
</script>
