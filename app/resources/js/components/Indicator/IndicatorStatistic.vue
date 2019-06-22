<template>
  <div class="indicatorStatistic" :stretched="stretched">
    <h2>{{title}}</h2>
    <div class="chart">
      <Chart :id="title" :type="graph" :datasets="datasets" :options="options"/>
    </div>
    <div class="legend">
      <div class="label" v-for="label in labels" :key="label">
        <div label :style="{ backgroundColor: colors[labels.indexOf(label)] }"/>
        <h3>{{label}}</h3>
      </div>
    </div>
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
      colors: ["#344669", "#3C8376", "#3ec940", "#dde02c", "#dd9c2c", "#af3838"]
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
