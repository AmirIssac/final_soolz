@extends('layouts.app')

@section('content')
<div style="display: flex; flex-direction:column; margin-top:100px; " class="container">
    
    @if ($message = Session::get('successAdd'))
<div class="alert alert-success alert-block">
	<button type="button" class="close" data-dismiss="alert">×</button>	
        <strong>{{ $message }}</strong>
</div>
@endif
@if ($message = Session::get('successRevoke'))
<div class="alert alert-success alert-block">
	<button type="button" class="close" data-dismiss="alert">×</button>	
        <strong>{{ $message }}</strong>
</div>
@endif
{{--
@foreach($users as $user)
<div style="display: flex;margin-top:20px; background-color: #b2d1f3; border-radius:10px; color:whitesmoke">
    <div style="background-color: #1ed59f; border-radius:10px" class="container-fluid row justify-content-center">
<h5 style="color: #393939; font-weight:bold" class="f m-1"> {{$user->email}} </h5>
    </div>
<div class="d-flex flex-row-reverse w-100">
    @foreach($roles as $role)
        @if(!$user->hasRole($role->name))
        <form action="{{route('add.role' , $user->id)}}" method="post">
            {!! csrf_field() !!}
            <input type="hidden" name="role" value="{{$role->name}}">
            <button   type="submit" class="btn btn-success m-1 float-right"> {{$role->name}} تعيين  </button>
        </form>
        @else
        <form action="{{route('revoke.role' , $user->id)}}" method="post">
            {!! csrf_field() !!}
            <input type="hidden" name="role" value="{{$role->name}}">
        <button type="submit"  class="btn btn-warning m-1 float-right">  {{$role->name}} </button>
        </form>
        @endif
    @endforeach
</div>
</div>
@endforeach --}}

<input style="direction: rtl" type="search" name="searchuser" class="form-control" placeholder="اكتب للبحث.." id="search">
<table class="table table-bordered m-1" id="mytable">
    <thead class="thead-light">
        <tr>
            <th><h5 class="float-right">تحكم كامل بأدوار المستخدمين</th>
        </tr>
    </thead>
    <tbody>
            <tr>
            <th>الايميل</th>
            @foreach($roles as $role)
            <th> {{$role->name}} </th>
            @endforeach
            </tr>
            @foreach($users as $user)
            <tr>
            <td>{{$user->email}}</td>
            @foreach($roles as $role)
            <td>
                @if(!$user->hasRole($role->name))
                 <form action="{{route('add.role' , $user->id)}}" method="post">
                     {!! csrf_field() !!}
                    <input type="hidden" name="role" value="{{$role->name}}">
                    <button   type="submit" class="btn btn-primary"> {{$role->name}} </button>
                </form>
                 @else
                    <form action="{{route('revoke.role' , $user->id)}}" method="post">
                     {!! csrf_field() !!}
                    <input type="hidden" name="role" value="{{$role->name}}">
                    <button type="submit"  class="btn btn-success suc">  {{$role->name}} </button>
                 </form>
                 @endif
            </td>
            @endforeach
            </tr>
            @endforeach
    </tbody>
</table>


</div>
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
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
@endsection
