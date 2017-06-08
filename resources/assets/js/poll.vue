<template>
	<div id="preview_container" data-sticky-container>
		<div class="panel panel-default" id="preview" data-margin-top="80">
			<div class="panel-title">
				<h1 v-if="poll.name">{{ poll.name }}</h1>
				<h1 v-else="poll.name">Name of Your Poll</h1>
			</div>
			<div class="panel-body">
				<div id="questions">
					<div id="questions_inner" :style="questionsInnerStyles">
						<div id="question" :style="{ width: (100/poll.questions.length) + '%' }" v-for="(question, index) in poll.questions">
							<h2 v-if="question.question">{{ question.question }}</h2>
							<h2 v-else="question.question">Question {{ index+1 }}</h2>
							
							<div class="radio" v-for="(answer, answer_index) in question.answers">
							  <label v-if="answer"><input type="radio" name="answer">{{ answer.answer }}</label>
							  <label v-else><input type="radio" name="answer">Answer {{ answer_index+1 }}</label>
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
					<div class="btn btn-default" id="next" @click="next" :disabled="this.current_index < this.poll.questions.length ? false : true">Next</div>
				</div>
				<div style="clear:both"></div>
			</div>
		</div>
	</div>
</template>

<style type="text/css">

	#questions {
	  width: 100%;
	  overflow: hidden;
	}
	#questions_inner{
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

	#question{
    	float: left;
	}
	.panel-title{
		border-bottom: 1px solid #f1f1f1;
	    padding-left:20px;
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

	#preview_container{
		margin-top:-80px;
	}

	#preview{
		top:0px !important;
		margin-top:80px;
	}
</style>

<script>
	var Sticky = require('sticky-js');

	module.exports = {

		props: ['poll'],

		data: function(){
			return {
				scrolled: 0,
				current_index: 1,
				questionsInnerStyles: {
					marginLeft: '0%', 
					width: '100%'
				},
				inner_offset: 0
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
			this.computeQuestionsInner();
			var sticky = new Sticky('#preview');
		}

	}
</script>