<template>
	<div id="poll-question">
		<i class="voyager-handle move-question"></i>
		<p class="delete-question" v-on:click="$emit('delete-question')">
			Remove Question
			<i class="voyager-trash"></i>
		</p>
		<div class="form-group">
            <label for="name">Question {{ index+1 }}</label>
            <input type="text" class="form-control" name="question" v-model="question.question" placeholder="Add a question here" value="">
        </div>

        <label for="answer">Answers</label>
        <draggable v-model="question.answers">
		    <transition-group>
		        <div v-for="(answer, id) in question.answers" :key="id">
		            <div class="answer">
		            	<i class="voyager-handle"></i>
		            	<input type="text" name="answers[]" class="form-control" v-model="question.answers[id].answer" :placeholder="'Add answer ' + (id+1)" @keyup.enter="newAnswerEnter(id)">
		            	<i class="voyager-trash" @click="deleteAnswer(id)"></i>
		            </div>
		        </div>
		    </transition-group>
		</draggable>

		<button class="btn btn-default btn-new" @click="newAnswer"><i class="voyager-plus"></i> New Answer</button>
	</div>
</template>

<style type="text/css">

	#poll-question{
		padding: 30px;
	    border: 1px solid #f1f1f1;
	    border-radius: 5px;
	    margin-bottom:20px;
	    position:relative;
	}

	#poll-question i.voyager-handle.move-question{
		position: absolute;
	    top: 0px;
	    left: 0px;
	    background: #f9f9f9;
	    color: #ccc;
	    padding: 4px 7px;
	    cursor: move;
	    border-bottom-right-radius: 10px;
	    border-top-left-radius: 3px;
	    padding-top: 5px;
	    padding-bottom: 3px;
	}

	.delete-question{
		position: absolute;
	    right: 6px;
	    bottom: -15px;
	    border-top-right-radius: 3px;
	    border-bottom-left-radius: 3px;
	    padding: 10px;
	    color: #E74C3C;
	    cursor: pointer;
	    font-size:10px;
	}

	.delete-question i{
		font-size:14px;
		float:right;
		position:relative;
		left:5px;
		top:-2px;
	}

	.answer{
		position:relative;
		padding-bottom:10px;
	}
	.answer i{
		position: absolute;
	    top: 1px;
	    height: 32px;
	    background: #f9f9f9;
	    width: 32px;
	    text-align: center;
	    line-height: 37px;
	    border: 0px;
	    border-right: 0px;
	    cursor: move;
	}
	.answer i.voyager-handle{
		left: 1px;
		border-top-left-radius: 3px;
	    border-bottom-left-radius: 3px;
	}
	.answer i.voyager-trash{
		right:1px;
		background:#e74c3c;
		border-top-right-radius: 3px;
		border-bottom-right-radius:3px;
		color:#fff;
		cursor:pointer;
	}
	.answer input{
		width:100%;
		padding-left:40px;
	}

	.btn-new i{
		position:relative;
		top:2px;
	}

</style>

<script>

	var draggable = require('vuedraggable');

	module.exports = {

		props: ['question', 'index'],

		data: function(){
			return {
				
			}
		},

		components: {
          draggable:draggable,
      	},

		methods: {
			newAnswer: function(){
				// Add a blank answer to the array
				this.question.answers.push({ id: '', answer: '' });
				this.$nextTick(function () {
					var answers = this.$el.getElementsByClassName('answer');
					answers[answers.length-1].querySelector('input').focus();
				});
			},

			deleteAnswer: function(index){
				if (index > -1) {
				    this.question.answers.splice(index, 1);
				}
			},
			newAnswerEnter: function(id){
				if(this.question.answers.length == id + 1){
					this.newAnswer();
				}
			}
		},

	}
</script>