<template>
  <button type="button" @click="handleGoBack">
    <ArrowUturnLeftIcon class="h-[25px] ml-4" />
  </button>
</template>
<script setup>
import { ArrowUturnLeftIcon } from '@heroicons/vue/20/solid';

const handleGoBack = () => {
  window.location.href = '/sales';
};
</script>
