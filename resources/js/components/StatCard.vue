<script setup lang="ts">
defineProps<{
    title: string;
    value: number | string;
    iconClass?: string;
    iconBgClass?: string;
}>();
</script>

<template>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 flex items-center">
        <div :class="['p-3 rounded-full mr-4', iconBgClass, iconClass]">
            <slot name="icon"></slot>
        </div>
        <div>
            <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">{{ title }}</p>
            <p class="text-3xl font-bold text-gray-800 dark:text-white">{{ value }}</p>
        </div>
    </div>
</template>
