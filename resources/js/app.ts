import '../css/app.css';

import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import { initializeTheme } from './composables/useAppearance';

const app = createApp(App);

app.use(router);
app.mount('#app');

// This will set light / dark mode on page load...
initializeTheme();
