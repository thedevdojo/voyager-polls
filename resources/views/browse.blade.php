@extends('voyager::master')

@section('css')

	<style type="text/css">
		#vueify .panel-body, #vueify .panel-bordered>.panel-body{
			padding:0px;
			padding-top:0px;
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
							
							<table id="dataTable" class="table table-hover dataTable no-footer" role="grid" aria-describedby="dataTable_info">
	                            <thead>
	                                <tr role="row">
	                                	<th class="sorting">Name</th>
	                                	<th class="sorting">Slug</th>
	                                	<th class="sorting">Created At</th>
	                                	<th class="sorting">Avatar</th>
	                                	<th class="actions sorting">Actions</th></tr>
	                            </thead>
	                            <tbody>

	                            	@foreach($polls as $poll)

	                            		<tr role="row" class="odd">
	                            			<td>{{ $poll->name }}</td>
	                            			<td>{{ $poll->slug }}</td>
	                            			<td>{{ Carbon\Carbon::parse($poll->created_at)->toDayDateTimeString() }}</td>
	                            			<td>{{ Carbon\Carbon::parse($poll->modified_at)->toDayDateTimeString() }}</td>
	                            			<td>
	                            				<div class="btn-sm btn-danger pull-right delete" data-id="1" id="delete-1">
	                                                <i class="voyager-trash"></i> Delete
	                                            </div>
	                                            <a href="http://poll.dev/admin/users/1/edit" class="btn-sm btn-primary pull-right edit">
	                                                <i class="voyager-edit"></i> Edit
	                                            </a>
	                                            <a href="http://poll.dev/admin/users/1" class="btn-sm btn-warning pull-right">
	                                                <i class="voyager-eye"></i> View
	                                            </a>
	                                        </td>
	                            		</tr>


	                            	@endforeach
	                                                            
	                            </tbody>
	                        </table>

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