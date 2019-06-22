<template>
  <div class="indicatorStatistic" :stretched="stretched">
    <div class="chart">
      <Chart :id="title" :type="graph" :labels="labels" :datasets="datasets" :options="options"/>
    </div>
    <h2>{{title}}</h2>
  </div>
</template>
<script>
import Chart from "../helpers/Chart";

export default {
  components: {
    Chart
  },
  props: ["indicator", "stretched"],
  data() {
    const i = this.indicator;

    var options = {
      legend: {
        display: false
      },
      plugins: {
        datalabels: {
          color: "white",
          textShadowColor: "black",
          textShadowBlur: 10,
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

    if (i.graph === "bar" || i.graph === "line") {
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
      labels: i.labels,
      options: options,
      colors: ["#344669", "#3C8376", "#58B6C0", "#7F8FA9", "#84ACB6", "#75BDA7"]
    };
  },
  computed: {
    datasets() {
      const data = this.indicator.data;
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
};
</script>
