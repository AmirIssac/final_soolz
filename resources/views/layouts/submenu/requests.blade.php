@extends('layouts.app')

@section('content')
<div style="display: flex; flex-direction:column; margin-top:100px; " class="container">
@foreach($applicants as $applicant)
<div style="display: flex;margin-top:20px; background-color: slategray; padding:10px; border-radius:10px; color:whitesmoke">
<h4 style="margin: auto"> {{$applicant->email}} </h4>
<form action="{{route('accept.request' , $applicant->id)}}" method="post">
    {!! csrf_field() !!}
    <button style="margin: auto"  type="submit" class="btn btn-success"> قبول </button>
</form>
</div>
@endforeach
</div>
@endsection
