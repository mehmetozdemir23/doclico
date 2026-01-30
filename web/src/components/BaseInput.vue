<template>
  <div>
    <label
      v-if="label"
      :for="id"
      class="block text-[13px] font-medium text-slate-700 mb-1.5"
    >
      {{ label }}
    </label>
    <input
      :id="id"
      :type="type"
      :value="modelValue"
      :required="required"
      :disabled="disabled"
      :placeholder="placeholder"
      :minlength="minlength"
      :maxlength="maxlength"
      :class="inputClasses"
      @input="$emit('update:modelValue', $event.target.value)"
    />
    <p v-if="hint" class="mt-1 text-[11px] text-slate-500">
      {{ hint }}
    </p>
  </div>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
  id: {
    type: String,
    required: true,
  },
  label: {
    type: String,
    default: null,
  },
  type: {
    type: String,
    default: "text",
  },
  modelValue: {
    type: [String, Number],
    default: "",
  },
  required: {
    type: Boolean,
    default: false,
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  placeholder: {
    type: String,
    default: "",
  },
  hint: {
    type: String,
    default: null,
  },
  minlength: {
    type: [String, Number],
    default: null,
  },
  maxlength: {
    type: [String, Number],
    default: null,
  },
  size: {
    type: String,
    default: "md",
    validator: (value) => ["sm", "md"].includes(value),
  },
});

defineEmits(["update:modelValue"]);

const inputClasses = computed(() => {
  const baseClasses =
    "w-full px-3 bg-white border border-slate-200 rounded-lg focus:outline-none focus:border-slate-400 transition-colors placeholder:text-slate-400";
  const sizeClasses =
    props.size === "sm" ? "h-10 text-[13px]" : "h-10 text-[15px]";
  const disabledClasses = props.disabled ? "opacity-50 cursor-not-allowed" : "";

  return `${baseClasses} ${sizeClasses} ${disabledClasses}`;
});
</script>
