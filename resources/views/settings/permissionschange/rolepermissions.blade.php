@extends('layouts.settings.default')
@push('css_lib')
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('plugins/iCheck/flat/blue.css')}}">
@endpush
@section('settings_title',trans('lang.permission_table'))
<style>
    button{
        float: right
    }
    input{
        float:right;
    }
</style>
@section('settings_content')
@if ($message = Session::get('success'))
<div class="alert alert-success alert-block">
	<button type="button" class="close" data-dismiss="alert">×</button>	
        <strong>{{ $message }}</strong>
</div>
@endif
<div class="card">
    <div class="card-header">
        <form action="{{route('edit.permissions.for.role',$role->id)}}" method="post">
        @csrf
        <ul class="nav nav-tabs  card-header-tabs w-100 flex-column">
            <li style="padding: 20px;" class="nav-item">
                <h4 class="text-success">{{$role->name}} &nbsp; تعديل صلاحيات</h4>
            </li>
            @foreach($all_permissions as $permission)
            <li style="padding: 20px;" class="nav-item">
                @if ($role->hasPermissionTo($permission->name))
                    {{--{{$permission->name}} <button class="btn btn-success"></button>--}}
                    {{$permission->name}} <input type="checkbox" name="permissions[]" value="{{$permission->name}}" checked>
                    @else
                    {{--{{$permission->name}} <button class="btn btn-danger"></button>--}}
                    {{$permission->name}} <input type="checkbox" name="permissions[]" value="{{$permission->name}}">
                @endif
            </li>
            @endforeach
            <li style="padding: 20px;" class="nav-item">
                <button type="submit" class="btn btn-success">حفظ</button>
            </li>
        </ul>
        </form>
    </div>
</div>
@endsection
@push('scripts_lib')
    <!-- iCheck -->
    <script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
@endpush