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

	<div id="app">	

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
	                            				<div class="btn-sm btn-danger pull-right delete" data-id="{{ $poll->id }}" id="delete-1">
	                                                <i class="voyager-trash"></i> Delete
	                                            </div>
												<a href="{{ route('voyager.polls.edit', ['id' => $poll->id]) }}" class="btn-sm btn-primary pull-right edit">
													<i class="voyager-edit"></i> Edit
												</a>
												<a href="{{ route('voyager.polls.read', ['id' => $poll->id]) }}" class="btn-sm btn-warning pull-right">
													<i class="voyager-eye"></i> View
												</a>
	                                        </td>
	                            		</tr>


	                            	@endforeach
	                                                            
	                            </tbody>
	                        </table>

						</div>
					</div>

			</div>
		</div>

		<div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
	        <div class="modal-dialog">
	            <div class="modal-content">
	                <div class="modal-header">
	                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
	                                aria-hidden="true">&times;</span></button>
	                    <h4 class="modal-title"><i class="voyager-trash"></i> Are you sure you want to delete
	                        this Poll?</h4>
	                </div>
	                <div class="modal-footer">
						<form action="{{ route('voyager.polls.delete') }}" id="delete_form" method="POST">
	                        {{ method_field("DELETE") }}
	                        {{ csrf_field() }}
	                        <input type="hidden" value="" id="delete_id" name="id">
	                        <input type="submit" class="btn btn-danger pull-right delete-confirm"
	                                 value="Yes, delete this poll">
	                    </form>
	                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Cancel</button>
	                </div>
	            </div><!-- /.modal-content -->
	        </div><!-- /.modal-dialog -->
	    </div><!-- /.modal -->

	</div>
</div>
	

@endsection

@section('javascript')
	<script>
		$('document').ready(function(){
			$('td').on('click', '.delete', function (e) {
	            var form = $('#delete_form')[0];

	            $('#delete_id').val( $(this).data('id') );
	            console.log(form.action);

	            $('#delete_modal').modal('show');
	        });
		});
	</script>
@endsection