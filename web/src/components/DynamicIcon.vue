<script setup>
import * as icons from "lucide-vue-next";
import { computed } from "vue";

const props = defineProps({
  name: {
    type: String,
    required: true,
  },
});

const icon = computed(() => {
  return icons[props.name] || icons.FileText;
});
</script>

<template>
  <component :is="icon" />
</template>
