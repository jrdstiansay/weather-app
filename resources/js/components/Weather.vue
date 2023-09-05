<script setup>
import { ref, onBeforeMount } from 'vue';
import CurrentWeatherDetails from './CurrentWeatherDetails.vue';
import ForecastDetails from './ForecastDetails.vue';
import axios from 'axios';
import { places, days, months } from '../constants';

/**
 * Data from the OpenWeather
 * @type {object}
 */
const currentWeatherData = ref(null);

/**
 * The list conatining the forecast for the follwing 5 days
 * @type {array}
 */
const fiveDaysList = ref([]);

/**
 * The variable in charge of showing the weather container
 * @type {boolean}
 */
const show = ref(false);

const /** @type {string} */ searchTerm = ref('');
const /** @type {boolean} */ isLoading = ref(false);
const /** @type {object} */ resultsList = ref([]);
const /** @type {boolean} */ showResults = ref(false);
const /** @type {object} */ jpPlaces = ref(places);
const /** @type {object} */ currentPlace = ref({});
const /** @type {object} */ selectedPlace = ref({});

/**
 * Get weather for the location searched
 * @param {decimal} lon Longitude
 * @param {decimal} lat Latitude
 */
const getWeather = async (lon, lat) => {
  let res = await axios.get('api/weather', {
    params: {
      lon,
      lat,
    },
  });

  /**
   * Details of latest weather
   * @type {object}
   */
  let latestWeather = null;

  /**
  * Details of latest weather
  * @type {object}
  */
  let tempFDays = [];

  let tempDate = new Date().getDate();

  for (let i = 0; i < res.data.list.length; i++) {
    
    /**
     * Get date get the latest weather by time
     * @type {date}
     */
    const dateFormat = Date.parse(res.data.list[i].dt_txt);
    const activeDate = new Date(res.data.list[i].dt_txt);

    /**
     * Filter the following days
     */
    if (activeDate.getDate() > tempDate) {
      
      tempDate = activeDate.getDate();

      tempFDays.push({
        date: months[activeDate.getMonth()] + ' ' + activeDate.getDate() + ', ' + activeDate.getFullYear(),
        day: days[activeDate.getDay()],
        weather: res.data.list[i].weather[0].main,
        temp: parseInt(res.data.list[i].main.temp, 10),
        humidity: res.data.list[i].main.humidity,
        feels_like: parseInt(res.data.list[i].main.feels_like, 10),
        temp_min: parseInt(res.data.list[i].main.temp_min, 10),
        temp_max: parseInt(res.data.list[i].main.temp_max, 10),
        icon: res.data.list[i].weather[0].icon,
      });
    }

    /**
     * Get the first and latest weather
     */
    if (dateFormat > new Date() && latestWeather == null) {
      latestWeather = {
        date: months[activeDate.getMonth()] + ' ' + activeDate.getDate() + ', ' + activeDate.getFullYear(),
        weather: res.data.list[i].weather[0].main,
        temp: parseInt(res.data.list[i].main.temp, 10),
        humidity: res.data.list[i].main.humidity,
        feels_like: parseInt(res.data.list[i].main.feels_like, 10),
        temp_min: parseInt(res.data.list[i].main.temp_min, 10),
        temp_max: parseInt(res.data.list[i].main.temp_max, 10),
        icon: res.data.list[i].weather[0].icon,
      };
    }
  }
  res.data.list = latestWeather;

  return {
    allData: res.data,
    fiveDays: tempFDays,
  };
};

/**
 * Hide autocomplete
 */
const hideResults = () => {
  showResults.value = false;
}

/**
 * Search in foursquare API
 */
const searchPlaces = async () => {
  if (searchTerm.value === '') {
    return []
  } else {
    isLoading.value = true;
    const res = await axios.get('api/place', {
      params: {
        q: searchTerm.value,
        near: currentPlace.value.near,
      },
    });
    isLoading.value = false;
    showResults.value = true;
    resultsList.value = res.data.results;
  }

};

/**
 * Remove check on second click
 * @param {object} place 
 */
const removeCheck = (place) => {
  if (currentPlace) {
    if (parseInt(place.id, 10) == parseInt(currentPlace.value.id, 10)) {
      currentPlace.value = {};
    } else {
      currentPlace.value = place;
    }
  }
};

/**
 * Select place and display the weather
 */
const selectPlace = async (place) => {
  searchTerm.value = place.name;
  selectedPlace.value = place;
  showResults.value = false;
  const data = await getWeather(place.geocodes.main.longitude, place.geocodes.main.latitude);
  fiveDaysList.value = data.fiveDays;
  currentWeatherData.value = data.allData;
};

/**
 * Change to current location's or tokyo's weather
 */
const restoreDefault = () => {

  let /** @type {object} */ data = {};

  const assignData = () => {
    fiveDaysList.value = data.fiveDays;
    currentWeatherData.value = data.allData;
    selectedPlace.value = {};
    currentPlace.value = {};
  }

  /**
   * Success function after getting the current location.
   * @param {object} position 
   */
  const success = async (position) => {
    data = await getWeather(position.coords.longitude, position.coords.latitude);
    assignData();
  };

  /**
   * Error function after getting the current location.
   * Will use Tokyo's lon & lat
   */
  const fallback = async () => {
    data = await getWeather(places[0].lon, places[0].lat);
    assignData();
  };

  /**
   * Function for getting current position
   */
  navigator.geolocation.getCurrentPosition(success, fallback);
};

onBeforeMount(async () => {
  await restoreDefault();
});
</script>
<template>
  <div class="main-bg">
    <h1 class="title">Japanese Weather App</h1>
    <div class="container mx-auto px-5">
      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6">
        <div v-for="place in jpPlaces" class="w-full">
          <input type="radio" :value="place" :id="'option' + place.id" name="choices" v-model="currentPlace"
            class="peer appearance-none" />
          <label @click="removeCheck(place)"
            class="block text-center cursor-pointer uppercase font-semibold text-lg rounded-lg px-5 py-2 peer-checked:border peer-checked:border-gray-200 peer-checked:bg-white">
            {{ place.name }}
          </label>
        </div>
      </div>
      <transition name="bounce">
        <div v-if="currentPlace.id" class="relative shadow-md text-gray-600 focus-within:text-gray-400 mt-16">
          <span class="absolute inset-y-0 right-1 flex items-center pl-2">
            <button type="submit" class="p-1 focus:outline-none focus:shadow-outline" @click="restoreDefault">
              <span v-if="isLoading">
                <i class="animate-spin bi bi-hourglass-bottom"></i>
              </span>
              <span v-else>
                <i class="bi bi-geo-alt-fill"></i>
              </span>
            </button>
          </span>
          <input type="text" name="q" @blur="hideResults" @input="searchPlaces" v-model="searchTerm" class="search-input"
            placeholder="Search..." autocomplete="off" />
          <transition name="fade" appear>
            <div 
              v-if="resultsList.length && showResults"
              class="absolute bg-white shadow w-full -mt-1 rounded-b-md px-6 py-3 z-40"
            >
              <ul class="list-none">
                <li 
                  v-for="result in resultsList"
                  :key="result.fsq_id"
                  class="cursor-pointer py-2 hover:text-black"
                  @click="selectPlace(result)"
                >
                  {{ result.name }}
                </li>
              </ul>
            </div>
          </transition>
        </div>
      </transition>
      <transition name="bounce">
        <div v-if="selectedPlace.fsq_id">
          <div class="flex text-white bg-teal-950 p-5 shadow-md items-center mt-8 rounded-lg">
            <img
              :src="selectedPlace.categories[0].icon.prefix + '64' + selectedPlace.categories[0].icon.suffix"
              alt="icon"
            />
            <div>
              <div class="text-lg font-bold uppercase">
                {{ selectedPlace.name }}
              </div>
              <div class="text-md font-italic uppercase mt-3">
                {{ selectedPlace.location.formatted_address }}
              </div>
            </div>
          </div>
        </div>
      </transition>
      <current-weather-details :weather="currentWeatherData" />
      <div class="grid grid-cols-1 lg:grid-cols-5 gap-4 mt-5">
        <forecast-details
          v-for="weather in fiveDaysList"
          :weather="weather" 
        />
      </div>
    </div>
  </div>
</template>