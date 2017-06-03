window.Vue = require('vue');
Vue.component('poll-question', require('./poll-question.vue'));
Vue.component('poll', require('./poll-preview.vue'));

var draggable = require('vuedraggable');

var vm = new Vue({
	el: '#vueify',
	data: {
		name: '',
		polls: []
	},
	methods:{
		newPoll: function(){
			return {
				question: '',
				answers: ['', '', '']
			}
		},
		createNewPoll: function(){
			this.polls.push(this.newPoll());
		}
	},
	created: function(){
		this.createNewPoll();
	},
	components: {
		draggable:draggable,
	},
});

// poll: {
// 			question: 'What color is the sky?',
// 			answers: ['Red', 'Green', 'Blue']
// 		},

