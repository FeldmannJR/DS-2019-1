<template>
  <div class="settings">
    <div class="indicatorsList">
      <div class="indicator" v-for="indicator in indicatorsList" :key="indicator.name">
        <div class="preview">
          <IndicatorPanel :indicator="indicator" :scale="0.1" />
        </div>
        <div class="indicatorSettings">
          <h4>{{indicator.name}}</h4>
          <v-form>
            <v-text-field label="Texto" prepend-icon="title" v-model="indicator.text" />
            <v-select
              v-if="indicator.graph"
              v-model="indicator.graph"
              label="Gráfico"
              :items="Object.keys(graphs).map((graph) => {
                return { text: graphs[graph], value: graph }
              })"
              prepend-icon="bar_chart"
            ></v-select>
          </v-form>
        </div>
        <div class="save" :disabled="!updated(indicatorsList.indexOf(indicator))">
          <v-tooltip bottom>
            <template v-slot:activator="{ on }">
              <v-icon :disabled="!updated(indicatorsList.indexOf(indicator))" v-on="on">save</v-icon>
            </template>
            <span>Salvar</span>
          </v-tooltip>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
require("./Settings.scss");
import IndicatorPanel from "../../components/IndicatorPanel";

export default {
  components: {
    IndicatorPanel
  },
  props: ["fixed", "indicators"],
  data() {
    return {
      headers: [
        { text: "Indicador", value: "name", sortable: false },
        { text: "Texto", value: "text", sortable: false }
      ],
      graphs: {
        bar: "Barras",
        pie: "Pizza",
        doughnut: "Rosca",
        none: "Nenhum"
      },
      original: this.indicators.map(i => ({ ...i })),
      indicatorsList: []
    };
  },
  methods: {
    updated(index) {
      return (
        JSON.stringify(this.indicatorsList[index]) !=
        JSON.stringify(this.original[index])
      );
    }
  },
  created() {
    this.indicatorsList = this.indicators;
  }
};
</script>