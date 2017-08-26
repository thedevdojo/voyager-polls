<template>
	<div class="poll-container" data-sticky-container>
		<div class="poll-loaded" v-if="loaded">
			<div class="poll-loading" v-show="loading">
				<div class="poll-loader"><div></div></div>
			</div>
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
												<div class="percentage-mask"></div>
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
						<div class="btn btn-default" id="next" @click="next" :disabled="((this.current_index < this.poll.questions.length && this.poll.questions[this.current_index-1].answered) || isPreview) ? false : true">Next</div>
					</div>
					<div style="clear:both"></div>
				</div>
			</div>
		</div><!-- .poll-loaded -->
		<div class="poll-loading" v-else>
			<div class="poll-loader">
			    <div></div>
			</div>
		</div>
	</div>
</template>

<style type="text/css">

	.poll-panel{
		text-align:left;
	}
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
		left:0px;
	}

	.answer_result .poll-percentage-bar span{
		content:'';
		width:0%;
		height:100%;
		background:#3498db;
		position:absolute;
	}

	.answer_result .poll-percentage-bar .percentage-mask{
		position:absolute;
		width:100%;
		height:100%;
		background:#fff;
		right:0px;
		top:0px;
		animation-duration: 1.5s;
  		animation-fill-mode: both;
  		animation-timing-function: ease-in;
  		animation-name: poll-mask-uncover;
  		z-index:9;
	}

	@keyframes poll-mask-uncover {
	  from {
	    transform-origin: right center;
	    width:100%;
	  }

	  to {
	    transform-origin: right center;
	    width:0%;
	  }
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

	.poll-loading{
		background:#f1f1f1;
	    display: block;
	    min-height: 200px;
	    width: 100%;
	    margin-top:80px;
	    border-radius:4px;
	    position:relative;
	}

	.poll-loaded{
		position:relative;
	}

	.poll-loaded .poll-loading{
	    position: absolute;
	    z-index: 9;
	    margin-top: 0px;
	    background: rgba(255, 255, 255, 0.7);
	    width: 100%;
	    height: 100%;
	    left: 0px;
	    top: 0px;
	}

	/********** LOADER CSS **********/

	.poll-loader,
	.poll-loader > div {
	    position: relative;
	    -webkit-box-sizing: border-box;
	       -moz-box-sizing: border-box;
	            box-sizing: border-box;
	}
	.poll-loader {
		display: block;
	    font-size: 0;
	    color: #ccc;
	    width: 32px;
	    height: 32px;
	    left:50%;
	    margin-left:-16px;
	    top:50%;
	    margin-top:-16px;
	    position:absolute;
	}
	
	.poll-loader > div {
		display: inline-block;
	    float: none;
	    background-color: currentColor;
	    border: 0 solid currentColor;
	    width: 32px;
	    height: 32px;
	    background: transparent;
	    border-width: 2px;
	    border-bottom-color: transparent;
	    border-radius: 100%;
	    -webkit-animation: poll-rotate .75s linear infinite;
	       -moz-animation: poll-rotate .75s linear infinite;
	         -o-animation: poll-rotate .75s linear infinite;
	            animation: poll-rotate .75s linear infinite;
	}
	/*
	 * Animation
	 */
	@-webkit-keyframes poll-rotate {
	    0% {
	        -webkit-transform: rotate(0deg);
	                transform: rotate(0deg);
	    }
	    50% {
	        -webkit-transform: rotate(180deg);
	                transform: rotate(180deg);
	    }
	    100% {
	        -webkit-transform: rotate(360deg);
	                transform: rotate(360deg);
	    }
	}
	@-moz-keyframes poll-rotate {
	    0% {
	        -moz-transform: rotate(0deg);
	             transform: rotate(0deg);
	    }
	    50% {
	        -moz-transform: rotate(180deg);
	             transform: rotate(180deg);
	    }
	    100% {
	        -moz-transform: rotate(360deg);
	             transform: rotate(360deg);
	    }
	}
	@-o-keyframes poll-rotate {
	    0% {
	        -o-transform: rotate(0deg);
	           transform: rotate(0deg);
	    }
	    50% {
	        -o-transform: rotate(180deg);
	           transform: rotate(180deg);
	    }
	    100% {
	        -o-transform: rotate(360deg);
	           transform: rotate(360deg);
	    }
	}
	@keyframes poll-rotate {
	    0% {
	        -webkit-transform: rotate(0deg);
	           -moz-transform: rotate(0deg);
	             -o-transform: rotate(0deg);
	                transform: rotate(0deg);
	    }
	    50% {
	        -webkit-transform: rotate(180deg);
	           -moz-transform: rotate(180deg);
	             -o-transform: rotate(180deg);
	                transform: rotate(180deg);
	    }
	    100% {
	        -webkit-transform: rotate(360deg);
	           -moz-transform: rotate(360deg);
	             -o-transform: rotate(360deg);
	                transform: rotate(360deg);
	    }
	}
</style>

<script>
	//var Sticky = require('sticky-js');
	var axios = require('axios');

	module.exports = {

		props: ['slug', 'poll', 'prefix'],

		data: function(){
			return {
				scrolled: 0,
				current_index: 1,
				questionsInnerStyles: {
					marginLeft: '0%', 
					width: '100%'
				},
				inner_offset: 0,
				loaded: false,
				loading: false,
				isPreview: false,
				routePrefix: '',
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
				if(typeof(answer.id) != "undefined" && !this.isPreview){
					this.loading = true;
					var question_answer = answer;
					var question_answered = question;
					var that = this;
					axios.post(this.routePrefix + '/polls/api/vote/' + question_answer.id)
						.then(function (response) {
							if(response.data.question_id){
								question_answer.votes += 1;
								that.questionAnswered(question_answered, question_answer);
								localStorage.setItem("poll_question_" + question_answered.id, question_answer.id);
								that.loading = false;
							}
						})
						.catch(function (error) {
							console.log(error.message);
						});
				}
			},
			questionAnswered: function(question, answer){
				question.total_votes = 0;
				for(var i = 0; i < question.answers.length; i++){
					 question.total_votes += question.answers[i].votes;
				}

				for(var i = 0; i < question.answers.length; i++){
					question.answers[i].vote_percentage = parseInt(100*(question.answers[i].votes/question.total_votes));
				}
				
				question.answered = answer.id;
				
			},
			createEmptyPoll: function(){
				this.poll = {
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
				};
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
			
			if(process.env.MIX_ROUTE_PREFIX){
				this.routePrefix = '/' + process.env.MIX_ROUTE_PREFIX;
			} else {
				if(this.prefix){
					this.routePrefix = '/' + this.prefix;
				}
			}

			if(this.slug){
				var that = this;
				axios.get(this.routePrefix + '/polls/api/' + this.slug + '.json')
						.then(function (response) {
							that.loaded = true;
							that.poll = response.data;
							for(var i = 0; i < that.poll.questions.length; i++){
								var answered = parseInt(localStorage.getItem("poll_question_" + that.poll.questions[i].id));
								if(answered){
									for(var j = 0; j < that.poll.questions[i].answers.length; j++){
										if(that.poll.questions[i].answers[j].id == answered){
											that.questionAnswered(that.poll.questions[i], that.poll.questions[i].answers[j]);
											break;
										}
									}
									
								}
							}
						})
						.catch(function (error) {
							console.log(error.message);
						});
			} else {
				this.loaded = true;
				this.isPreview = true;
				this.computeQuestionsInner();
				this.createEmptyPoll();
				var sticky = new Sticky('#preview');
			}
		}

	}
</script>
