<template>
  <component
    :is="to ? 'router-link' : 'button'"
    :to="to"
    :type="!to ? type : undefined"
    :disabled="disabled"
    :class="buttonClasses"
    @click="handleClick"
  >
    <slot></slot>
  </component>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
  variant: {
    type: String,
    default: "primary",
    validator: (value) =>
      ["primary", "secondary", "danger", "ghost"].includes(value),
  },
  size: {
    type: String,
    default: "md",
    validator: (value) => ["sm", "md"].includes(value),
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  fullWidth: {
    type: Boolean,
    default: false,
  },
  type: {
    type: String,
    default: "button",
  },
  to: {
    type: [String, Object],
    default: null,
  },
});

const emit = defineEmits(["click"]);

const handleClick = (event) => {
  if (!props.disabled) {
    emit("click", event);
  }
};

const buttonClasses = computed(() => {
  const baseClasses =
    "inline-flex items-center justify-center gap-2 font-medium rounded-lg transition-colors";

  const sizeClasses = {
    sm: "h-8 px-3 text-[13px]",
    md: "h-9 px-4 text-[13px]",
  }[props.size];

  const variantClasses = {
    primary: "bg-slate-900 text-white hover:bg-slate-800",
    secondary: "text-slate-700 hover:bg-slate-100",
    danger: "text-red-600 hover:bg-red-50",
    ghost: "text-slate-500 hover:text-slate-900 hover:bg-slate-100",
  }[props.variant];

  const widthClasses = props.fullWidth ? "w-full" : "";
  const disabledClasses = props.disabled ? "opacity-75 cursor-not-allowed" : "";

  return `${baseClasses} ${sizeClasses} ${variantClasses} ${widthClasses} ${disabledClasses}`;
});
</script>
