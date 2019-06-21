<template>
  <div class="indicatorStatistic">
    <Chart :id="title" :type="graph" :labels="labels" :datasets="datasets" :options="options"/>
  </div>
</template>
<script>
require("./IndicatorStatistic.scss");
import Chart from "../helpers/Chart";

export default {
  components: {
    Chart
  },
  props: ["indicator", "multiple"],
  data() {
    const size = this.multiple ? 0.5 : 1,
      i = this.indicator,
      colors = [
        "#344669",
        "#3C8376",
        "#58B6C0",
        "#7F8FA9",
        "#84ACB6",
        "#75BDA7"
      ],
      datasets = [
        {
          data: i.data,
          backgroundColor: i.data.map((d, i) => {
            return colors[i];
          })
        }
      ];
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
            size: window.innerWidth / 25
          }
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
      datasets: datasets,
      options: options
    };
  }
};
</script>
