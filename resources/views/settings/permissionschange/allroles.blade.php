@extends('layouts.settings.default')
@push('css_lib')
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('plugins/iCheck/flat/blue.css')}}">
@endpush
@section('settings_title',trans('lang.permission_table'))

@section('settings_content')
<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs align-items-start card-header-tabs w-100 flex-column">
            @foreach($roles as $role)
            <li style="padding: 20px;" class="nav-item">
                <a href="{{route('show.role.permissions',$role->id)}}">
                    {{$role->name}}
                </a>
            </li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
@push('scripts_lib')
    <!-- iCheck -->
    <script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
@endpush