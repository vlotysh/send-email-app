import './bootstrap';
import { useEmailStore } from '@/stores/emailStore'
import { createPinia } from 'pinia'
import { createApp } from 'vue';

const pinia = createPinia()
import App from './components/App.vue';

const app = createApp(App)
app.use(pinia)



app.mount('#app');
const emailStore = useEmailStore()

