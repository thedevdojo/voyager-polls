@extends('voyager::master')

@section('css')

	<style type="text/css">

		#poll_name{
			padding-bottom:30px;
			margin-bottom:30px;
			border-bottom:1px solid #f1f1f1;
		}

		#poll_name input{
			font-size: 20px;
	    	padding: 30px 20px;
		}

		.btn-question{
			margin-top:20px;
			display:block;
			width:auto;
			margin:20px auto;
		}

		.btn-question i{
			position:relative;
			top:2px;
		}

	</style>

@endsection

@section('content')

<div class="padding-top">

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

						<div class="col-md-12" id="poll_name">
							<input type="text" name="poll_name" placeholder="Give this poll a name" v-model="name" class="form-control">
						</div>


						<div class="col-md-6">
							<draggable v-model="polls">
		    					<transition-group>
									<poll-question v-for="(poll, index) in polls" :poll="poll" :index="index" :key="index"></poll-question>
								</transition-group>
							</draggable>
							<button class="btn btn-primary btn-question" @click="createNewPoll"><i class="voyager-question"></i> Another Question +</button>
	                    </div>

	                    <div class="col-md-6">
	                    	<label for="preview">Preview:</label>
	                    	<poll :polls="polls"></poll>
	                    </div>

					</div>
				</div>

			</div>
		</div>
	</div>
</div>
	

@endsection

@section('javascript')
	<script>
		<?php include(base_path('hooks/voyager-polls/app.js')); ?>
	</script>
@endsection