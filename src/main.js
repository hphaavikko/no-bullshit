import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import './assets/css/global.css';
import 'svg-injector';
import Vue from 'vue';
import App from './App.vue';

Vue.config.productionTip = false

export const eventBus = new Vue();

new Vue({
  render: h => h(App),
}).$mount('#app')
