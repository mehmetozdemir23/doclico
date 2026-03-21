<template>
  <Teleport to="body">
    <div
      v-if="modelValue"
      class="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4"
      role="dialog"
      aria-modal="true"
      :aria-labelledby="titleId"
      @keydown.escape="$emit('cancel')"
    >
      <div class="bg-white rounded-xl w-full max-w-sm p-6 space-y-4 shadow-xl">
        <h3 :id="titleId" class="text-[15px] font-semibold text-slate-900">{{ title }}</h3>
        <p v-if="message" class="text-[13px] text-slate-500">{{ message }}</p>
        <div class="flex gap-2 pt-1">
          <BaseButton :variant="confirmVariant" :full-width="true" @click="$emit('confirm')">
            {{ confirmLabel }}
          </BaseButton>
          <BaseButton variant="secondary" :full-width="true" @click="$emit('cancel')">
            Annuler
          </BaseButton>
        </div>
      </div>
    </div>
  </Teleport>
</template>

<script setup>
import { useId } from "vue";
import BaseButton from "./BaseButton.vue";

defineProps({
  modelValue: { type: Boolean, default: false },
  title: { type: String, required: true },
  message: { type: String, default: null },
  confirmLabel: { type: String, default: "Confirmer" },
  confirmVariant: { type: String, default: "danger" },
});

defineEmits(["confirm", "cancel"]);

const titleId = useId();
</script>
