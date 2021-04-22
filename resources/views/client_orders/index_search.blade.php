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
<div style="margin-top: 100px" class="card">
    <div class="card-body">
        <h5 class="card-title">البحث برقم الطلب للعميل</h5>
        <h6 class="card-subtitle mb-2 mt-1 text-muted">أدخل رقم الطلب</h6>
        <form action="{{route('make.search.order')}}" method="GET">
            @csrf
            <input type="number" name="order_id">
            <button type="submit" class="btn btn-primary"> بحث </button>
        </form>
    </div>
  </div>
@if(isset($order))
  <table class="table table-bordered m-1" id="mytable">
    <thead class="thead-light">
        <tr>
            <th><h5 class="float-right">معلومات الطلب </th>
        </tr>
    </thead>
    <tbody>
            <tr>
            <th>رقم الطلب</th>
            <th>العميل</th>
            <th>المطعم</th>
            <th>حالة الطلب</th>
            <th>حالة الدفع</th>
            <th>المبلغ</th>
            <tr>
            <td>{{$order->id}}</td>
            <td>{{$user->name}}</td>
            <td>
                @if(!isset($restaurant))    {{-- because old orders dont have specific restaurant in old versions --}}
                لم يجلب المطعم
                @else
                {{$restaurant->name}}</td>
                @endif
            <td>
            {{$order_status->status}}
        
            </td>
            <td>@if($payment->status=='Paid')
                <span class="badge badge-success">{{$payment->status}}</span>
                @else
                <span class="badge badge-warning">{{$payment->status}}</span>
                @endif
            </td>
            <td>{{$payment->price}}</td>
            </tr>
    </tbody>
</table>
@endif
@if(isset($noOrderFound))
<div class="alert alert-warning" role="alert">
    لا يوجد طلب بهذا الرقم في قاعدة البيانات يرجى التأكد من رقم الطلب
  </div>
  @endif
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
@endpush