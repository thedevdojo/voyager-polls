@extends('voyager::master')


@section('content')

	<div id="app">
		<poll-creator url="{{ env('APP_URL') }}" edit_poll="{{ @$poll }}"></poll-creator>
	</div>
	
@endsection

@section('javascript')
	<script>
		<?php include(VOYAGER_POLLS_PATH.'/app.js'); ?>
	</script>
@endsection