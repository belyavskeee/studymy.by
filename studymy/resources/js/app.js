import { createApp } from 'vue';
import App from '@/components/App.vue';
import Router from '@/router/router';
import Store from '@/store/store';

import BigLoader from '@/components/UI/BigLoader.vue';
import MyFooter from "@/components/MyFooter.vue";
import MyHeader from "@/components/MyHeader.vue";


const app = createApp(App);


app.component('big-loader', BigLoader);
app.component('my-footer', MyFooter);
app.component('my-header', MyHeader);

app.use(Router);
app.use(Store);
app.mount('#app');
