<template>
  <div class="indicatorPanel">
    {{update}}
    <!-- Renderiza indicador numerico -->
    <IndicatorNumeric
      v-if="indicator.type === 'numeric'"
      :indicator="indicator"
      :key="indicator.title"
    />
    <template v-else>
      <!-- Renderiza indicador múltiplo -->
      <IndicatorMultiple
        v-if="indicator.graph === 'none'"
        :indicator="indicator"
        :stretched="stretched"
        :multiple="multiple"
        :key="indicator.title"
      />
      <!-- Renderiza indicador múltiplo estatistico -->
      <IndicatorStatistic
        v-else
        :indicator="indicator"
        :stretched="stretched"
        :scale="scale"
        :key="indicator.title"
      />
    </template>
  </div>
</template>
<script>
import IndicatorNumeric from "./IndicatorNumeric";
import IndicatorMultiple from "./IndicatorMultiple";
import IndicatorStatistic from "./IndicatorStatistic";

export default {
  props: ["indicator", "multiple", "stretched", "scale", "update"],
  components: {
    IndicatorNumeric,
    IndicatorMultiple,
    IndicatorStatistic
  }
};
</script>
