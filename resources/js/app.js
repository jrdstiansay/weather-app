
import '../css/app.css';
import {createApp} from 'vue';
import Weather from './components/Weather.vue';

/**
 * Create a vue application
 */
const app = createApp({
  components: {
    Weather
  }
});

app.mount("#app");