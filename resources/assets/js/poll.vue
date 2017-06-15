<template>
	<div class="poll-container" v-if="loaded" data-sticky-container>
		<div class="panel panel-default poll-panel" data-margin-top="80">
			<div class="panel-title">
				<h1 v-if="poll.name">{{ poll.name }}</h1>
				<h1 v-else="poll.name">Name of Your Poll</h1>
			</div>
			<div class="panel-body">
				<div class="poll-questions">
					<div class="poll-questions-inner" :style="questionsInnerStyles">
						<div class="poll-question" :style="{ width: (100/poll.questions.length) + '%' }" v-for="(question, index) in poll.questions">
							<h2 v-if="question.question">{{ question.question }}</h2>
							<h2 v-else="question.question">Question {{ index+1 }}</h2>
							
							<div class="radio" v-for="(answer, answer_index) in question.answers">
								<div :class="[question.answered == answer.id ? 'answer-container question-answer' : 'answer-container']">
									<label v-if="answer"><input type="radio" :name="answer.id" @click="vote(answer,question)" :disabled="(typeof(question.answered) != 'undefined' && question.answered != '') ? true : false" :checked="question.answered == answer.id">{{ answer.answer }}</label>
								  	<label v-else><input type="radio" name="answer">Answer {{ answer_index+1 }}</label>
									<div class="answer_result" v-if="question.answered">
										<div class="poll-percentage-bar">
											<span :style="{ width: answer.vote_percentage + '%' }"></span>
										</div>
										<p>{{ answer.vote_percentage }}%</p>
									</div>
								</div>
							</div>
							
						</div>
					</div>
				</div>
			</div>
			<div class="panel-footer">
				<div class="poll_num">
					<p>Question {{ current_index }} of {{ poll.questions.length }}</p>
				</div>
				<div class="poll_buttons">
					<div class="btn btn-default" id="previous" @click="prev" :disabled="this.current_index != 1 ? false : true">Previous</div>
					<div class="btn btn-default" id="next" @click="next" :disabled="(this.current_index < this.poll.questions.length && this.poll.questions[this.current_index-1].answered) ? false : true">Next</div>
				</div>
				<div style="clear:both"></div>
			</div>
		</div>
	</div>
</template>

<style type="text/css">

	.poll-questions {
	  width: 100%;
	  overflow: hidden;
	}
	.poll-questions-inner{
		-webkit-transform: translateZ(0);
		-moz-transform: translateZ(0);
		-o-transform: translateZ(0);
		-ms-transform: translateZ(0);
		transform: translateZ(0);

		-webkit-transition: all 800ms cubic-bezier(0.770, 0.000, 0.175, 1.000);
		-moz-transition: all 800ms cubic-bezier(0.770, 0.000, 0.175, 1.000);
		-o-transition: all 800ms cubic-bezier(0.770, 0.000, 0.175, 1.000);
		-ms-transition: all 800ms cubic-bezier(0.770, 0.000, 0.175, 1.000);
		transition: all 800ms cubic-bezier(0.770, 0.000, 0.175, 1.000);

		-webkit-transition-timing-function: cubic-bezier(0.770, 0.000, 0.175, 1.000);
		-moz-transition-timing-function: cubic-bezier(0.770, 0.000, 0.175, 1.000);
		-o-transition-timing-function: cubic-bezier(0.770, 0.000, 0.175, 1.000);
		-ms-transition-timing-function: cubic-bezier(0.770, 0.000, 0.175, 1.000);
		transition-timing-function: cubic-bezier(0.770, 0.000, 0.175, 1.000);
	}

	.poll-question{
    	float: left;
	}
	.panel-title{
		border-bottom: 1px solid #f1f1f1;
	    padding-left:20px;
	}

	.answer_result{
		margin-bottom:10px;
		margin-top:5px;
	}

	.answer_result p{
		float:right;
		text-align:right;
	}

	.answer_result:after{
		content:'';
		clear:both;
		display:block;
	}

	.answer_result .poll-percentage-bar{
		width:92%;
		float:left;
		height:20px;
		border:1px solid #ccc;
		position:relative;
	}

	.answer_result .poll-percentage-bar span{
		content:'';
		width:0%;
		height:100%;
		background:#3498db;
		position:absolute;
		transition:width 500ms cubic-bezier(0.770, 0.000, 0.475, 1.000)
	}

	.panel-body{
		padding:20px;
	}

	.poll_num{
		float:left;
	}

	.poll_num p{
		line-height: 45px;
	    margin-bottom: 0px;
	    margin-left: 10px;
	    font-size:11px;
	    font-weight: 400;
	    text-transform: uppercase;
	    color: #bbb;
	}

	.poll_buttons{
		float:right;
	}

	h1{
		margin-bottom: 0px;
	    font-size: 36px;
	    color: #444;
	    font-weight: 200;
	}

	.radio{
		margin-left: 15px;
    	margin-top: 20px;
	}

	.sticky{
		top:0px !important;
	}

	h2{
		font-weight:200;
	}

	.poll-container{
		margin-top:-80px;
	}

	.poll-panel{
		top:0px !important;
		margin-top:80px;
	}
</style>

<script>
	var Sticky = require('sticky-js');
	var axios = require('axios');

	module.exports = {

		props: ['slug', 'poll'],

		data: function(){
			return {
				scrolled: 0,
				current_index: 1,
				questionsInnerStyles: {
					marginLeft: '0%', 
					width: '100%'
				},
				poll: {
					questions:{
						id:'',
						question: '',
						answers: [
							{ 'id': '', 'answer': '' },
							{ 'id': '', 'answer': '' },
							{ 'id': '', 'answer': '' }
						],
						answered: false,
					}
				},
				inner_offset: 0,
				loaded: false,
				isPreview: false,
			}
		},
		methods: {
			next: function() {
				if(this.current_index < this.poll.questions.length){
					this.inner_offset -= 100;
					this.current_index += 1;
				}
			},
			prev: function() {
				if(this.current_index != 1){
					this.inner_offset += 100;
					this.current_index -= 1;
				}
			},
			computeQuestionsInner: function(){
				this.questionsInnerStyles.marginLeft = this.inner_offset + '%';
				this.questionsInnerStyles.width = (100*this.poll.questions.length) + '%';
			},
			vote: function(answer, question){
				if(typeof(answer.id) != "undefined"){
					var question_answer = answer;
					var question_answered = question;
					var that = this;
					axios.post('/polls/api/vote/' + question_answer.id)
						.then(function (response) {
							if(response.data.question_id){
								question_answer.votes += 1;
								that.questionAnswered(question_answered, question_answer);
								localStorage.setItem("poll_question_" + question_answered.id, question_answer.id);
							} else {

							}
							
							console.log(response);
						})
						.catch(function (error) {
							console.log(error.message);
						});
				}
			},
			questionAnswered: function(question, answer){
				console.log('question/answer');
				console.log(question);
				console.log(answer);
				question.total_votes = 0;
				console.log(question.answers.length);
				for(var i = 0; i < question.answers.length; i++){
					 question.total_votes += question.answers[i].votes;
				}

				for(var i = 0; i < question.answers.length; i++){
					question.answers[i].vote_percentage = parseInt(100*(question.answers[i].votes/question.total_votes));
				}
				
				question.answered = answer.id;
				
			}
		},
		watch: {
			inner_offset: function(){
				this.computeQuestionsInner();
			},
			'poll.questions': function(){
				this.computeQuestionsInner();
			}
		},
		created: function(){
			axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
			
			if(this.slug){
				var that = this;
				axios.get('/polls/api/' + this.slug + '.json')
						.then(function (response) {
							that.loaded = true;
							that.poll = response.data;
							for(var i = 0; i < that.poll.questions.length; i++){
								var answered = parseInt(localStorage.getItem("poll_question_" + that.poll.questions[i].id));
								if(answered){
									//console.log('hit for poll_question_' + question.id );
									for(var j = 0; j < that.poll.questions[i].answers.length; j++){
										console.log('looped');
										if(that.poll.questions[i].answers[j].id = answered){
											that.questionAnswered(that.poll.questions[i], that.poll.questions[i].answers[j]);
											break;
										}
									}
									
								} else {
									//console.log('not hit for poll_question_' + question.id );
								}
							}
							console.log(response);
						})
						.catch(function (error) {
							console.log(error.message);
						});
			} else {
				this.loaded = true;
				this.isPreview = true;
				this.computeQuestionsInner();
				var sticky = new Sticky('#preview');
			}
		}

	}
</script>