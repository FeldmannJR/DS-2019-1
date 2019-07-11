<template>
  <div class="settings">
    <div class="slides">
      <div class="slidesList">
        <v-data-iterator :items="sortedPresentation" :rows-per-page-items="[2]">
          <template v-slot:item="props">
            <div @click="currentSlide = sortedPresentation.indexOf(props.item)">
              <Panel
                :fixed="fixed"
                :presentation="sortedPresentation"
                :scale=".09"
                :container="'slidePreview_' + sortedPresentation.indexOf(props.item)"
                :index="sortedPresentation.indexOf(props.item)"
                :stop="true"
                :selected="currentSlide == sortedPresentation.indexOf(props.item)"
                :key="props.item.order"
              />
            </div>
          </template>
        </v-data-iterator>
      </div>
      <Panel
        :fixed="fixed"
        :presentation="sortedPresentation"
        :scale=".5"
        :index="currentSlide"
        :stop="true"
        :container="'slideBigPreview'"
      />
      <v-form class="slideSettings">
        <v-text-field
          id="timerInput"
          label="Tempo"
          v-model="sortedPresentation[currentSlide].timer"
          type="number"
          min="0"
          max="3600"
          @keypress="checkTimer"
          @input="formatTimer"
        ></v-text-field>
        <v-select
          label="Ordem"
          v-model="sortedPresentation[currentSlide].order"
          :items="order"
          @input="formatOrder"
        ></v-select>
        <v-btn class="btnSave" :disabled="!updatedPresentation" @click="savePresentation">
          <v-icon>save</v-icon>Salvar
        </v-btn>
      </v-form>
    </div>
    <div class="indicatorsList">
      <div class="indicator" v-for="indicator in localIndicators" :key="indicator.name">
        <div class="preview">
          <IndicatorPanel :indicator="indicator" :scale="0.175" />
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
        <div class="save" :disabled="!updatedIndicator(localIndicators.indexOf(indicator))">
          <v-tooltip bottom>
            <template v-slot:activator="{ on }">
              <v-icon
                :disabled="!updatedIndicator(localIndicators.indexOf(indicator))"
                v-on="on"
                @click="saveIndicator(localIndicators.indexOf(indicator))"
              >save</v-icon>
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
import Panel from "../Panel/Panel";

export default {
  components: {
    IndicatorPanel,
    Panel
  },
  props: ["fixed", "presentation", "indicators"],
  data() {
    var sortedPresentation = this.presentation.sort((a, b) => {
      return a.order < b.order ? -1 : 1;
    });

    return {
      currentSlide: 0,
      graphs: {
        bar: "Barras",
        pie: "Pizza",
        doughnut: "Rosca",
        none: "Nenhum"
      },
      order: sortedPresentation.map(p => {
        return p.order;
      }),
      sortedPresentation: sortedPresentation,
      localIndicators: this.indicators,
      originalIndicators: this.indicators.map(i => ({ ...i })),
      originalPresentation: sortedPresentation.map(p => ({ ...p }))
    };
  },
  computed: {
    updatedPresentation() {
      return (
        JSON.stringify(this.originalPresentation) !=
        JSON.stringify(this.sortedPresentation)
      );
    }
  },
  methods: {
    savePresentation() {
      this.originalPresentation = JSON.parse(
        JSON.stringify(this.sortedPresentation)
      );
    },
    checkTimer(e) {
      if (e.key < "0" || e.key > "9") {
        e.preventDefault();
      }
    },
    formatTimer() {
      var timer = this.sortedPresentation[this.currentSlide].timer,
        value =
          timer
            .split("")
            .filter(char => {
              return char >= "0" && char <= "9";
            })
            .join("") || 0;
      timer = parseInt(value);
    },
    formatOrder(newOrder) {
      var order = this.currentSlide;
      newOrder--;
      if (order < newOrder) {
        for (var i = newOrder; i > order; i--) {
          this.sortedPresentation[i].order -= 1;
        }
      } else {
        for (var i = newOrder; i < order; i++) {
          this.sortedPresentation[i].order += 1;
        }
      }

      this.currentSlide = newOrder;
    },
    updatedIndicator(index) {
      return (
        JSON.stringify(this.localIndicators[index]) !=
        JSON.stringify(this.originalIndicators[index])
      );
    },
    saveIndicator(index) {
      this.originalIndicators[index] = JSON.parse(
        JSON.stringify(this.localIndicators[index])
      );
    }
  },
  created() {
    this.sortedPresentation = this.sortedPresentation.map(p => {
      return {
        timer: p.timer,
        order: p.order,
        slide: p.slide.map(row => {
          return row.map(i => {
            return this.localIndicators.filter(ind => {
              return ind.id == i;
            })[0];
          });
        })
      };
    });
  }
};
</script>