window.Vue = require('vue');
Vue.component('poll-question', require('./poll-question.vue'));
Vue.component('poll', require('./poll.vue'));
Vue.component('poll-creator', require('./poll-creator.vue'));

var vm = new Vue({ el: '#app' });