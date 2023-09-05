<script setup>
import { defineProps } from "vue";

const props = defineProps({
  weather: {
    required: true
  }

})

</script>
<template>
  <transition name="bounce" appear>
    <div v-if="props.weather">
      <div class="text-xl font-semibold mt-4 lg:mt-10 mb-4">
        {{ props.weather.list.date }}
      </div>
      <div class="current-weather-container">
        <div class="weather-details">
          <div class="other-details">
            <div class="mb-2 w-full lg:w-fit text-center">
              <span class="font-semibold">
                Humidity:
              </span>
              {{ props.weather.list.humidity }}
              g.m<sup>-3</sup>
            </div>
            <div class="mb-2 w-full lg:w-fit text-center">
              <span class="font-semibold">
                Max Temp:
              </span>
              {{ props.weather.list.temp_max }}°C
            </div>
            <div class="mb-2 lg:mb-0 w-full lg:w-fit text-center">
              <span class="font-semibold">
                Min Temp:
              </span>
              {{ props.weather.list.temp_min }}°C
            </div>
          </div>
          <div class="main-details">
            <h1 class="main-temp">{{ props.weather.list.temp }}°C</h1>
            <div class="mt-3">Feels Like {{ props.weather.list.feels_like }}°C</div>
          </div>
          <div class="details">
            <div class="main">
              {{ props.weather.list.weather }}
            </div>
            <div class="city">
              {{ props.weather.city.name }}, {{ props.weather.city.country }}
            </div>
          </div>
        </div>
        <div class="flex justify-center lg:justify-right">
          <img
            :src="'https://openweathermap.org/img/wn/' + props.weather.list.icon + '@2x.png'"
            alt="weather"
          />
        </div>
      </div>
    </div>
  </transition>
</template>
