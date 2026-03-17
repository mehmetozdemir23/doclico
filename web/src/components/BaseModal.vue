<template>
  <Teleport to="body">
    <div
      v-if="modelValue"
      data-modal
      role="dialog"
      aria-modal="true"
      :aria-labelledby="titleId"
      class="fixed inset-0 bg-black/40 flex items-end sm:items-center justify-center z-50 sm:p-4"
      @click.self="$emit('update:modelValue', false)"
      @keydown.escape="$emit('update:modelValue', false)"
    >
      <div
        class="bg-white rounded-t-xl sm:rounded-xl w-full flex flex-col max-h-[90vh]"
        :class="sizeClass"
      >
        <div class="flex items-center justify-between px-5 pt-5 pb-4 border-b border-slate-100 flex-shrink-0">
          <h3 :id="titleId" class="text-[15px] font-semibold text-slate-900">{{ title }}</h3>
          <button
            class="text-slate-400 hover:text-slate-600 transition-colors"
            aria-label="Fermer"
            @click="$emit('update:modelValue', false)"
          >
            <X class="w-4 h-4" aria-hidden="true" />
          </button>
        </div>
        <div class="overflow-y-auto flex-1">
          <slot />
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
import { computed, useId } from "vue";
import { X } from "lucide-vue-next";

const props = defineProps({
  modelValue: Boolean,
  title: String,
  size: { type: String, default: "md" },
});

defineEmits(["update:modelValue"]);

const titleId = useId();

const sizeClass = computed(() => ({
  sm: "sm:max-w-sm",
  md: "sm:max-w-md",
  lg: "sm:max-w-lg",
}[props.size] ?? "sm:max-w-md"));
</script>
