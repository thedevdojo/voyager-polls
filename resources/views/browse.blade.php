@extends('voyager::master')

@section('css')

	<style type="text/css">
		#polls{
			
		}
	</style>

@endsection

@section('content')

<div class="padding-top">

	<div id="vueify">	

		<h1 class="page-title">
        	<i class="voyager-bar-chart"></i> Polls
            <a href="{{ route('voyager.polls.add') }}" class="btn btn-success">
				<i class="voyager-plus"></i> Add New
            </a>
        </h1>

		<div id="polls">
			<div class="container-fluid">

					<div class="panel panel-bordered">
						<div class="panel-heading">
							<h3 class="panel-title">Polls</h3>
						</div>
						<div class="panel-body">
							asdf
						</div>
					</div>

					<p>test</p>
					<poll-creator></poll-creator>

					<div class="panel-body">

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