<template>
  <div>
    <div v-if="type === 'numeric'">{{text}}</div>
    <div v-else></div>
  </div>
</template>
<script>
export default {
  props: ["text", "type", "value", "icon", "values", "graph"]
};
</script>
