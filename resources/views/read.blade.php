@extends('voyager::master')

@section('css')

	<style type="text/css">
		.poll-results-meter{
			width:100%;
			float:right;
		}
		.label-bar{
			display:block; 
			text-align:left;
			padding: 8px 10px;
    		margin-top: 2px;
			margin-bottom:10px;
		}
		ul.poll-results:after{
			content:'';
			clear:both;
			display:block;
		}
		ul.poll-results{
			margin-bottom: 40px;
    		margin-top: 20px;
		}
		.polls-back{
			position: relative;
    		top: -18px;
		}
		.polls-back i{
			position: relative;
    		top: 1px;
		}
		.poll-container h1{
			margin-top:0px;
		}
	</style>

@endsection

@section('content')

<div class="padding-top">

	<div id="app">	

		<h1 class="page-title">
        	<i class="voyager-bar-chart"></i> {{ $poll->name }}
        </h1>
       

		<div id="polls">
			<div class="container-fluid">
				<div class="col-md-12">
					<a href="{{ url(env('ROUTE_PREFIX') . '/admin/polls') }}" class="polls-back"><i class="voyager-angle-left"></i> Back to all Polls</a>
				</div>
				<div class="col-md-6">
					<poll slug="{{ $poll->slug }}" prefix="{{ env('ROUTE_PREFIX') }}"></poll>
				</div>
				<div class="col-md-6">
					<div class="panel panel-default" data-margin-top="80">
						<div class="panel-title">
							<h2>Results</h2>
						</div>
						<div class="panel-body">
							@foreach($poll->questions as $question)
								@php $total_votes = $question->totalVotes(); @endphp
								<h4>{{ $question->question }} <small class="label label-success">{{ $total_votes }} Total Votes</small></h4>
								<ul class="poll-results">
								@foreach($question->answers as $answer)
									@php $percentage = 0; @endphp
									@if($total_votes != 0)
										@php $percentage = intval(100*($answer->votes/$total_votes)); @endphp
									@endif
									<li>{{ $answer->answer }}<span class="poll-results-meter"><span class="label label-default label-bar" style="width:{{ $percentage }}%">{{ $percentage }}% with <b>{{ $answer->votes }}</b> votes</span></span></li>
								@endforeach
								</ul>
							@endforeach
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