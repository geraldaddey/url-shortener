
require('./bootstrap');

window.Vue = require('vue').default;


Vue.component('url-shortener', require('./components/UrlShortener.vue').default);


const app = new Vue({
    el: '#app',
});
