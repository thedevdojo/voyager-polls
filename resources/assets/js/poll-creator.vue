<template>
	<div id="vueify">
		<h1 class="page-title">
	    	<i class="voyager-bar-chart"></i> New Poll
	    </h1>

		<div id="polls">
			
			<div class="container-fluid">

				<div class="panel panel-bordered">
					
					<div class="panel-heading">
						<h3 class="panel-title">Add Your New Poll Below</h3>
					</div>

					<div class="panel-body">

						<div class="col-md-6" id="poll_name">
							<input type="text" name="poll_name" placeholder="Give this poll a name" v-model="poll.name" class="form-control">
						</div>

						<div class="col-md-6" id="poll_slug">
							<input type="text" name="poll_slug" placeholder="Poll Slug (Unique Identifier)" v-model="poll.slug" class="form-control">
						</div>


						<div class="col-md-6">
							<draggable v-model="poll.questions">
		    					<transition-group>
									<poll-question v-for="(this_poll, index) in poll.questions" :poll="this_poll" :index="index" :key="index" v-on:delete-question="deleteQuestion(index)"></poll-question>
								</transition-group>
							</draggable>
							<button class="btn btn-primary btn-question" @click="createNewQuestion"><i class="voyager-question"></i> Another Question +</button>
	                    </div>

	                    <div class="col-md-6">
	                    	<label for="preview">Preview:</label>
	                    	<poll-preview :poll="poll"></poll-preview>
	                    </div>

					</div>
					<div class="panel-footer">
						<button class="btn btn-primary pull-right" id="create" @click="savePoll" v-html="saveCopy"></button>
						<div style="clear:both"></div>
					</div>
				</div>

			</div>
		</div>

	</div>
</template>

<style type="text/css">
		#poll_name, #poll_slug{
			padding-bottom:30px;
			margin-bottom:30px;
			border-bottom:1px solid #f1f1f1;
		}

		#poll_name input, #poll_slug input{
			font-size: 20px;
	    	padding: 30px 20px;
		}

		.btn-question{
			margin-top:20px;
			display:block;
			width:auto;
			margin:20px auto;
		}

		.voyager-refresh{
			-webkit-animation: spin 0.6s infinite linear;
		    -moz-animation: spin 0.6s infinite linear;
		    animation: spin 0.6s infinite linear;
			display: inline-block;
		    width: 18px;
		    height: auto;
		    transform-origin: 8px 9px;
		    position: relative;
		    top: 2px;
		}

		.btn-question i{
			position:relative;
			top:2px;
		}
</style>

<script>
	var draggable = require('vuedraggable');
	var axios = require('axios');
	var slugify = require('slugify');
	var newQuestionCopy = 'Create New Question';
	var newQuestionLoadingCopy = '<span class="voyager-refresh"></span> Saving New Poll';
	var updateQuestionCopy = 'Update Question';


	module.exports = {
		
		props: ['url', 'edit_poll'],

		data: function(){
			return {
				saveCopy: newQuestionCopy,
				poll: {
					name:'',
					slug: '',
					questions:[]
				}
			}
			
		},
		watch: {
			'poll.name': function(){
				this.poll.slug = slugify(this.poll.name).toLowerCase();
			}
		},
		methods:{
			newQuestion: function(){
				return {
					question: '',
					answers: ['', '', '']
				}
			},
			createNewQuestion: function(){
				this.poll.questions.push(this.newQuestion());
			},
			savePoll: function(){
				this.saveCopy = newQuestionLoadingCopy;
				axios.post(this.url + '/admin/polls/add', {
					poll: this.poll
				  })
				  .then(function (response) {
				    console.log(response);
				    this.saveCopy = updateQuestionCopy;
				  })
				  .catch(function (error) {
				  	this.saveCopy = newQuestionCopy;
				    console.log(error);
				  });
			},
			deleteQuestion: function(index){
				this.poll.questions.splice(index, 1);
			}
		},
		created: function(){
			this.createNewQuestion();
			if(this.edit_poll){
				this.poll = this.edit_poll;
			}
		},
		components: {
			draggable:draggable,
		},
	};
</script>