
@extends('layouts.app')
@push('css_lib')
<!-- iCheck -->
<link rel="stylesheet" href="{{asset('plugins/iCheck/flat/blue.css')}}">
<!-- select2 -->
<link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
<!-- bootstrap wysihtml5 - text editor -->
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
{{--dropzone--}}
<link rel="stylesheet" href="{{asset('plugins/dropzone/bootstrap.min.css')}}">
@endpush
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{trans('lang.food_plural')}} <small>{{trans('lang.food_desc')}}</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
          <li class="breadcrumb-item"><a href="{!! route('foods.index') !!}">{{trans('lang.food_plural')}}</a>
          </li>
          <li class="breadcrumb-item active">{{trans('lang.food_create')}}</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
  <div class="clearfix"></div>
  @include('flash::message')
</div>


<!-- add extras to specific food of specific restaurant -->
<div style="margin-left: 100px; display:flex; flex-direction:column;" class="form-group row">
<form  action="{{route('add.extras.for.food',$foodid)}}" method="POST">
  @csrf
  <label> هل تريد اضافة اضافات لهذا المنتج <strong>{{$foodname}}</strong>  </label>
    <br>
  <label> التابع لمطعمك <strong>{{$resname}}</strong> </label>
  <div>
  {!! Form::select('extras', $extras, null, ['multiple'=>'multiple','name'=>'extras[]','class' => 'select2 form-control']) !!}
  <div class="form-text text-muted">انقر هنا لتحديد الإضافات</div>
  </div>
  <div style="margin-top: 10px">
  <button style="width:100px !important;" type="submit" class="btn btn-primary"> حفظ </button>
  </div>
</form>
{{--<div style="margin-top: 10px">
<a style="width:100px !important;" role="button" class="btn btn-danger" href="{{route('foods.index')}}"> الغاء </a>
</div>--}}
</div>


@include('layouts.media_modal')
@endsection
@push('scripts_lib')
<!-- iCheck -->
<script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
<!-- select2 -->
<script src="{{asset('plugins/select2/select2.min.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
{{--dropzone--}}
<script src="{{asset('plugins/dropzone/dropzone.js')}}"></script>
<script type="text/javascript">
    Dropzone.autoDiscover = false;
    var dropzoneFields = [];
</script>
<script>
  <script src="{{asset('plugins/slimScroll/jquery.slimscroll.min.js')}}"></script>
  <script src="{{asset('js/ajax.js')}}">     {{--  ajax --}}
@endpush
