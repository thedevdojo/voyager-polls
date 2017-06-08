window.Vue = require('vue');
Vue.component('poll-question', require('./poll-question.vue'));
Vue.component('poll-preview', require('./poll-preview.vue'));
Vue.component('poll-creator', require('./poll-creator.vue'));

var vm = new Vue({ el: '#app' });