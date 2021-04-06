@extends('layouts.settings.default')
@push('css_lib')
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('plugins/iCheck/flat/blue.css')}}">
@endpush
@section('settings_title',trans('lang.permission_table'))

@section('settings_content')
<input style="direction: rtl" type="search" name="searchuser" class="form-control" placeholder="اكتب للبحث.." id="search">
<table class="table table-bordered m-3" id="mytable">
    <thead class="thead-light">
        <tr>
            <th><h5 class="float-right">تعديل صلاحيات المستخدم</th>
        </tr>
    </thead>
    <tbody>
            <tr>
            <th>اسم المستخدم</th>
            <th>الايميل</th>
            <th>رقم الهاتف</th>
            <th>تعديل الصلاحيات</th>
            </tr>
            @foreach($users as $user)
            <tr>
            <td>{{$user->name}}</td>
            <td>{{$user->email}}</td>
            <td>{{$user->phone}}</td>
            <td><a class="btn btn-primary" href="{{route('show.user.permissions',$user->id)}}" role="button">تعديل الصلاحيات</a></td>
            </tr>
            @endforeach
    </tbody>
</table>
@endsection
@push('scripts_lib')
    <!-- iCheck -->
    <script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
    <script>
        $(document).ready(function(){
          $("#search").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#mytable tr").filter(function() {
              $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
          });
        });
        </script>
        
@endpush