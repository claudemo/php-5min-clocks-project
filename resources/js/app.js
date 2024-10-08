import axios from 'axios';
import { createApp } from 'vue';
import App from './App.vue';

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found. Please include it in your page.');
}


createApp(App).mount('#app');