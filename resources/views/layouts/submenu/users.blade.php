@extends('layouts.app')

@section('content')
<div style="display: flex; flex-direction:column; margin-top:100px; " class="container">
    
    @if ($message = Session::get('success'))
<div class="alert alert-success alert-block">
	<button type="button" class="close" data-dismiss="alert">×</button>	
        <strong>{{ $message }}</strong>
</div>
@endif

@foreach($users as $user)
<div style="display: flex;margin-top:20px; background-color: #b2d1f3; border-radius:10px; color:whitesmoke">
    <div style="background-color: #1ed59f; border-radius:10px" class="container-fluid row justify-content-center">
<h5 style="color: #393939; font-weight:bold" class="f m-1"> {{$user->email}} </h5>
    </div>
<div class="d-flex flex-row-reverse w-100">
@if(!$user->hasRole('admin'))
<form action="{{route('make.admin' , $user->id)}}" method="post">
    {!! csrf_field() !!}
    <button   type="submit" class="btn btn-success m-1 float-right"> تعيين كمدير </button>
</form>
@else
<button  class="btn btn-warning m-1 float-right">  مدير </button>
@endif

@if(!$user->hasRole('cashier'))
<form action="{{route('make.cashier' , $user->id)}}" method="post">
    {!! csrf_field() !!}
    <button style="margin: auto"  type="submit" class="btn btn-success m-1"> تعيين ككاشير </button>
</form>
@else
<button  class="btn btn-warning m-1 float-right">  كاشير </button>
@endif

@if(!$user->hasRole('driver'))
<form action="{{route('make.driver' , $user->id)}}" method="post">
    {!! csrf_field() !!}
    <button style="margin: auto"  type="submit" class="btn btn-success m-1"> تعيين كسائق </button>
</form>
@else
<button  class="btn btn-warning m-1 float-right">  سائق </button>
@endif

@if(!$user->hasRole('client'))
<form action="{{route('make.client' , $user->id)}}" method="post" class="float-right">
    {!! csrf_field() !!}
    <button   type="submit" class="btn btn-success m-1 float-right"> تعيين كزبون </button>
</form>
@else
<button  class="btn btn-warning m-1 float-right">  زبون </button>
@endif
</div>
</div>
@endforeach
</div>
@endsection
