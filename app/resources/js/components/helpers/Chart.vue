<template>
  <canvas :id="id"></canvas>
</template>
<script>
export default {
  props: ["id", "type", "labels", "datasets", "options"],
  mounted() {
    var ctx = document.getElementById(this.id).getContext("2d");
    var chart = new Chart(ctx, {
      // The type of chart we want to create
      type: this.type || "bar",

      // The data for our dataset
      data: {
        labels: this.labels,
        datasets: this.datasets
      },

      // Configuration options go here
      options: this.options
    });
  }
};
</script>
