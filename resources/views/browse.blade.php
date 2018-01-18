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
                <i class="voyager-bar-chart"></i> {{ __('polls.polls') }}
                <a href="{{ route('voyager.polls.add') }}" class="btn btn-success">
                    <i class="voyager-plus"></i> {{ __('voyager.generic.add_new') }}
                </a>
            </h1>

            <div id="polls">
                <div class="container-fluid">

                    <div class="panel panel-bordered">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ __('polls.polls') }}</h3>
                        </div>
                        <div class="panel-body">

                            <table id="dataTable" class="table table-hover dataTable no-footer" role="grid" aria-describedby="dataTable_info">
                                <thead>
                                <tr role="row">
                                    <th class="sorting">{{ __('voyager.generic.name') }}</th>
                                    <th class="sorting">{{ __('polls.slug') }}</th>
                                    <th class="sorting">{{ __('polls.created_at') }}</th>
                                    <th class="sorting">{{ __('polls.updated_at') }}</th>
                                    <th class="actions sorting">{{ __('voyager.generic.actions') }}</th></tr>
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
                                                <i class="voyager-trash"></i><span class=" hidden-xs hidden-sm"> {{ __('voyager.generic.delete') }}</span>
                                            </div>
                                            <a href="{{ route('voyager.polls.edit', ['id' => $poll->id]) }}" class="btn-sm btn-primary pull-right edit">
                                                <i class="voyager-edit"></i><span class=" hidden-xs hidden-sm"> {{ __('voyager.generic.edit') }}</span>
                                            </a>
                                            <a href="{{ route('voyager.polls.read', ['id' => $poll->id]) }}" class="btn-sm btn-warning pull-right">
                                                <i class="voyager-eye"></i><span class=" hidden-xs hidden-sm"> {{ __('voyager.generic.read') }}</span>
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