<script setup>
import { defineProps } from "vue";

const props = defineProps({
  weather: {
    required: true,
  }
});
</script>
<template>
  <transition name="bounce" appear>
    <div v-if="props.weather">
      <div class="shadow-md bg-white rounded-md pb-8">
        <div class="flex justify-center lg:justify-right">
          <img 
            :src="'https://openweathermap.org/img/wn/' +
              props.weather.icon +
              '@2x.png'
            " alt="weather" />
        </div>
        <div class="uppercase text-xl font-bold w-full text-center mb-4">
          {{ props.weather.weather }}
        </div>
        <div class="uppercase text-md font-semibold w-full text-center mb-4">
          {{ props.weather.date }}
        </div>
        <div class="uppercase text-lg font-semibold w-full text-center">
          {{ props.weather.day }}
        </div>
      </div>
    </div>
  </transition>
</template>
