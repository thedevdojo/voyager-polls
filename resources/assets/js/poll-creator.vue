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
									<poll-question v-for="(question, index) in poll.questions" :question="question" :index="index" :key="index" v-on:delete-question="deleteQuestion(index)"></poll-question>
								</transition-group>
							</draggable>
							<button class="btn btn-primary btn-question" @click="createNewQuestion"><i class="voyager-question"></i> Another Question +</button>
	                    </div>

	                    <div class="col-md-6">
	                    	<label for="preview">Preview:</label>
	                    	<poll :poll="poll"></poll>
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


	module.exports = {
		
		props: ['url', 'edit_poll'],

		data: function(){
			return {
				newQuestionCopy : 'Create New Question',
				newQuestionLoadingCopy: '<span class="voyager-refresh"></span> Saving New Poll',
				updateQuestionCopy : 'Update Question',
				saveCopy: '',
				post_url: '',
				poll: {
					id: '',
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
					id:'',
					question: '',
					answers: [
						{ 'id': '', 'answer': '' },
						{ 'id': '', 'answer': '' },
						{ 'id': '', 'answer': '' }
					]
				}
			},
			createNewQuestion: function(){
				this.poll.questions.push(this.newQuestion());
			},
			savePoll: function(){
				this.saveCopy = this.newQuestionLoadingCopy;
				
				var that = this;
				axios.post(this.post_url, { poll: this.poll })
					.then(function (response) {
						if(response.data.status == "success"){
							toastr.success(response.data.message);
							that.saveCopy = that.updateQuestionCopy;
							that.newQuestionCopy = that.updateQuestionCopy;
							that.post_url = that.url + '/admin/polls/edit';
							that.poll = response.data.poll;
							history.replaceState(null, null, '/admin/polls/' + that.poll.id + '/edit');
						} else {
							toastr.error(response.data.message);
							that.saveCopy = that.newQuestionCopy;
						}
				    
				  	})
					.catch(function (error) {
						that.saveCopy = that.newQuestionCopy;
						toastr.error(error.message);
					});
			},
			deleteQuestion: function(index){
				this.poll.questions.splice(index, 1);
			}
		},
		created: function(){
			if(this.edit_poll){
				this.poll = JSON.parse(this.edit_poll);
				this.newQuestionCopy = this.updateQuestionCopy;
				this.post_url = this.url + '/admin/polls/edit';
			} else {
				this.createNewQuestion();
				this.post_url = this.url + '/admin/polls/add';
			}

			this.saveCopy = this.newQuestionCopy;
		},
		components: {
			draggable:draggable,
		},
	};
</script>