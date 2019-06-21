<template>
  <svg icon :class="className" :viewBox="viewBox || '0 0 24 24'">
    <path :d="icon"></path>
  </svg>
</template>
<script>
export default {
  props: ["className", "viewBox", "icon"]
};
</script>
