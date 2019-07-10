<template>
  <div class="indicatorPanel">
    <!-- Renderiza indicador numerico -->
    <IndicatorNumeric v-if="i.type === 'numeric'" :indicator="i" :key="i.name" />
    <template v-else>
      <!-- Renderiza indicador múltiplo -->
      <IndicatorMultiple
        v-if="indicator.graph === 'none'"
        :indicator="i"
        :stretched="stretched"
        :multiple="multiple"
        :key="i.name"
      />
      <!-- Renderiza indicador múltiplo estatistico -->
      <IndicatorStatistic
        v-else
        :indicator="i"
        :stretched="stretched"
        :scale="scale"
        :key="i.name"
      />
    </template>
  </div>
</template>
<script>
import IndicatorNumeric from "./IndicatorNumeric";
import IndicatorMultiple from "./IndicatorMultiple";
import IndicatorStatistic from "./IndicatorStatistic";

export default {
  props: ["indicator", "multiple", "stretched", "scale"],
  components: {
    IndicatorNumeric,
    IndicatorMultiple,
    IndicatorStatistic
  },
  data() {
    return {
      i: this.indicator
    };
  }
};
</script>
