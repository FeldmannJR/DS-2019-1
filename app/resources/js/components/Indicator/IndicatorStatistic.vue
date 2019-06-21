<template>
  <div class="indicatorStatistic">
    <Chart :id="title" :type="graph" :labels="labels" :datasets="datasets" :options="options"/>
  </div>
</template>
<script>
require("./IndicatorNumeric.scss");
import Chart from "../helpers/Chart";

export default {
  components: {
    Chart
  },
  props: ["indicator", "multiple"],
  data() {
    const i = this.indicator,
      colors = [
        "#344669",
        "#3C8376",
        "#58B6C0",
        "#7F8FA9",
        "#84ACB6",
        "#75BDA7"
      ],
      datasets = i.datasets.map((dataset, i) => {
        return {
          label: dataset.label,
          data: dataset.data,
          backgroundColor: colors[i]
        };
      });

    return {
      title: i.title,
      graph: i.graph,
      labels: i.labels,
      datasets: datasets,
      options: {
        legend: {
          position: "bottom"
        },
        scales: {
          yAxes: [
            {
              display: true,
              ticks: {
                suggestedMin: 0
              }
            }
          ]
        }
      }
    };
  }
};
</script>
