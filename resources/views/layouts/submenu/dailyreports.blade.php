@extends('layouts.app')

@section('content')
<style>
    .con{
        margin: 50px 50px 0 50px;
        display: flex;
        flex-direction: column;
        justify-content:space-around;
    }

img{
    width: 75px;
    height: 100px;
}
img:hover{
    cursor: pointer;
}
</style>
<div class="con">
    @foreach($reports as $report)
    <?php $payments = $report->payments;
            $sum =0;
    ?>
    <!--<table style="width:100%; margin-top:75px;"> -->
        <table class="table table-bordered m-3">
            <thead class="thead-dark">
                <tr>
                    <th><h5 class="float-right">التقرير رقم {{($report->id)}}</h5>   {{$report->created_at}}</th>
                </tr>
            </thead>
            <tbody>
                    <tr>
                    <th>رقم معرف الزبون</th>
                    <th>طريقة الدفع</th>
                    <th>حالة الدفع</th>
                    <th>المبلغ</th>
                    </tr>
                    @foreach($payments as $payment)
                    <tr>

                    <td>{{$payment->user_id}}</td>
                    <td>{{$payment->method}}</td>
                    <td>{{$payment->status}}</td>
                    <td>{{$payment->price}}</td>
                    </tr>
                    <?php
                    $sum+=$payment->price; 
                    ?>
                    @endforeach
                    <tr>
                        <td style="font-weight: bold">مجموع المبالغ</td>
                        <td>{{$sum}}</td>
                        <td><form action="{{route('print.pdf',$report->id)}}" method="POST">
                            {!! csrf_field() !!}
                            <button class="btn btn-danger"> pdf طباعة </button>
                        </td>
                    </tr>
            </tbody>
      </table>
    @endforeach
</div>
@endsection