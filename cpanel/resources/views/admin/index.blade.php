@extends('admin.partials.master')
@section('title'){{__('global.short_title')}}@endsection
@section('header_title'){{__('global.dashboard')}}@endsection
@section('head')
    @include('admin.inc.datatables-css')
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    @if(Session::has('success'))
                        <label class="alert alert-success w-100 alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                            {{Session::get('success')}}
                        </label>
                    @endif
                    @if(Session::has('error'))
                        <label class="alert alert-danger w-100 alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                            {{Session::get('error')}}
                        </label>
                    @endif
                        <h4 class="m-b-30 m-t-0">{{__('global.new_mail_task')}}</h4>
                        <form class="form" method="POST">
                            @csrf
                            <div class="row">
                            <div class="form-group col-12 col-md-4">
                                <label  for="group-id">{{__('global.group')}}</label><br>
                                <select id="group-id" name="group_id" class="form-control">
                                    @foreach($groups as $group)
                                        <option value="{{$group->id}}">{{$group->name}} - {{$group->country->name_en}} - {{$group->language->lang}}</option>
                                        @endforeach
                                </select>
                            </div>

                            <div class="form-group col-12 col-md-4">
                                <label  for="email-type">{{__('global.email_type')}}</label><br>
                                <select id="email-type" name="email_type" class="form-control">
                                    <!-- <option value="mail_en">{{__('global.marketing_en')}}</option>
                                    <option value="mail_ar">{{__('global.marketing_ar')}}</option> -->
                                    <option value="mail2_en">{{__('global.marketing_en')}}</option>
                                    <option value="mail2_ar">{{__('global.marketing_ar')}}</option>
                                </select>
                            </div>
                                <div class="form-group col-12 col-md-4">
                                    <label>. </label><br>
                            <button type="submit" class="btn btn-success waves-effect waves-light m-l-10">{{__('global.add_to_queue')}}</button>
                                </div>
                            </div>
                        </form>
                        <hr>
                        <h4 class="m-b-30 m-t-0">{{__('global.history')}}
                                <form method="POST" class="form float-right bulk-delete-form" method="POST" action="">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="bulk_delete" value="[]">
                                    <button href="#" class="float-right btn btn-danger" type="submit"><i class="mdi mdi-delete"></i> {{__('global.batch_delete')}}</button>
                                </form>
                        </h4>

                        <div class="table-responsive">
                            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>
                                        <div class="checkbox text-center checkbox-primary">
                                            <input id="checkbox--1" type="checkbox" value="-1" data-value="-1">
                                            <label for="checkbox--1">
                                            </label>
                                        </div>
                                    </th>
                                    <th>{{__('global.group')}}</th>
                                    <th>{{__('global.timestamp')}}</th>
                                    <th>{{__('global.report')}}</th>
                                    <th>{{__('global.actions')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($tasks->items() as $task)
                                    <tr>
                                        <td>
                                                <div class="checkbox text-center checkbox-primary">
                                                    <input id="checkbox-{{$task->id}}" type="checkbox" value="{{$task->id}}" data-value="{{$task->id}}">
                                                    <label for="checkbox-{{$task->id}}">
                                                    </label>
                                                </div>
                                        </td>
                                        <td>{{$task->group->name}}</td>
                                        <td>{{$task->created_at}}</td>
                                        <td>
                                            Processed: {{$task->job_statuses()->count()}}<br>
                                            Success: {{$task->job_statuses()->success()->count()}}<br>
                                            Fail: {{$task->job_statuses()->fail()->count()}}
                                        </td>
                                        <td>
                                                <a href="" class="btn btn-sm btn-info"><i class="mdi mdi-eye"></i></a>
                                                <form method="POST" class="form d-inline-block delete-form" method="POST" action="">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button href="#" class="btn btn-sm btn-danger"><i class="mdi mdi-delete"></i></button>
                                                </form>

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>

                            </table>
                        </div>
                        <div class="justify-content-center">
                            {!! $tasks->links() !!}
                        </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    @include('admin.inc.bulkdelete')
    @include('admin.inc.smarttoggles')
    @include('admin.inc.datatable')
@endsection
