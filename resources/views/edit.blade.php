@extends('app')

@section('page_level_styles')
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="{{ URL::asset('assets/global/plugins/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('assets/global/plugins/simple-line-icons/simple-line-icons.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('assets/global/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('assets/global/plugins/uniform/css/uniform.default.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/global/plugins/select2/select2.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}"/>
    <!-- END GLOBAL MANDATORY STYLES -->
@stop

@section('theme_level_styles')
    <link href="{{ URL::asset('assets/global/css/components-rounded.css')}}" id="style_components" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/global/css/plugins.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/admin/layout4/css/layout.css')}}" rel="stylesheet" type="text/css"/>
    <link id="style_color" href="{{ URL::asset('assets/admin/layout4/css/themes/light.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/admin/layout4/css/custom.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('css/layout.css')}}" rel="stylesheet" type="text/css"/>
    <link id="style_color" href="{{ URL::asset('css/themes/light.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('css/custom.css')}}" rel="stylesheet" type="text/css"/>
@stop

@section('content')
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            <div class="tab-content">
                <div class="tab-pane active" id="tab_0">
                    <div class="portlet box green">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-gift"></i> Update-Location
                                {{--@if(Session::has('flash_message'))--}}
                                    {{--<div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {!! session('flash_message') !!}</em></div>--}}
                                {{--@endif--}}
                                {{--@if(Session::has('flash_err_message'))--}}
                                    {{--<div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {!! session('flash_err_message') !!}</em></div>--}}
                                {{--@endif--}}
                            </div>
                            <div class="tools">
                                <a href="javascript:;" class="collapse">
                                </a>
                                <a href="#portlet-config" data-toggle="modal" class="config">
                                </a>
                                <a href="javascript:;" class="reload">
                                </a>
                                <a href="javascript:;" class="remove">
                                </a>
                            </div>
                        </div>
                        <div class="portlet-body form">
                        @foreach($edit_data as $value)
                            <!-- BEGIN FORM-->
                            <form action="{{url('home/update')}}/{{$value->id}}" class="form-horizontal" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                <input type="hidden" name="id" value="{{ $value->id }}" class="form-control">
                                <div class="form-body">
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Name</label>
                                        <div class="col-md-4">
                                            <input type="text" value="{{ $value->name }}" class="form-control input-circle" placeholder="Enter Name" name="name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Description</label>
                                        <div class="col-md-4">
                                            <input type="text" value="{{ $value->description }}" class="form-control input-circle" placeholder="Enter Description" name="description">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Address</label>
                                        <div class="col-md-4">
                                            <input type="text" value="{{ $value->address }}" class="form-control input-circle" placeholder="Enter Address" name="address">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Latitude</label>
                                        <div class="col-md-4">
                                            <input type="text" value="{{ $value->lat }}" class="form-control input-circle" placeholder="Enter Latitude" name="latitude">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Longitude</label>
                                        <div class="col-md-4">
                                            <input type="text" value="{{ $value->lng }}" class="form-control input-circle" placeholder="Enter Longitude" name="longitude">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Image</label>
                                        <div class="col-md-4">
                                            <input type="file" value="{{ $value->image }}" name="file" id="file">
                                            <img style="width: 80px;" src=/shahbaz/laravel/googlemap/public/upload/{{$value->image}}>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-offset-3 col-md-9">
                                            <button type="submit" class="btn btn-circle blue">Update</button>
                                            <a href="{{url('/home')}}"><button type="button" class="btn btn-circle default">Back</button></a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </form>
                            <!-- END FORM-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
@endsection

@section('page_level_plugins')
    <script type="text/javascript" src="{{ URL::asset('assets/global/plugins/select2/select2.min.js')}}"></script>
@stop

@section('page_level_scripts')
    <script src="{{ URL::asset('assets/global/scripts/metronic.js')}}" type="text/javascript"></script>
    <script src="{{ URL::asset('assets/admin/layout4/scripts/layout.js')}}" type="text/javascript"></script>
    <script src="{{ URL::asset('assets/admin/layout4/scripts/demo.js')}}" type="text/javascript"></script>
    <script src="{{ URL::asset('assets/admin/pages/scripts/form-samples.js')}}"></script>

@stop

@section('footer')
    <script language="JavaScript" type="text/javascript">
        var csrf_token ='{{ csrf_token() }}';
        var url_get_states='{{url('api/get_states')}}';
        var url_get_advertiser_widgets='{{url('api/get_advertiser_widgets')}}';
        var url_upload_ajax='{{url('datatable/upload')}}'
    </script>
@stop