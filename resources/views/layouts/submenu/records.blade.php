@extends('layouts.app')

@section('content')
<div style="width: 100%; height: 100vh; display:flex;justify-content: space-around;align-items: center">
    <style>
            button:hover{
                cursor: pointer;
            }
    </style>
    <form action="{{route('daily.reports')}}" method="GET">
        {!! csrf_field() !!}
        <button style="width: 200px; height: 50px; background-color: #007bff; color:white; border: 2px solid white;border-radius: 10px " type="submit"> التقارير اليومية </button>
    </form>
    <form action="" method="GET">
        {!! csrf_field() !!}
      
        <button class="btn"style="width: 200px; height: 50px; background-color: #007bff; color:white; border: 2px solid white;border-radius: 10px " type="submit"> التقارير الشهرية </button>
    </form>
</div>
@endsection