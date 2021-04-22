@extends('layouts.app')

@section('content')
<div style="display: flex; flex-direction:column; margin-top:100px; " class="container">
    <table class="table table-bordered m-1" id="mytable">
        <thead class="thead-light">
            <tr>
                <th><h5 class="float-right"><span class="badge badge-success">{{$user->name}}</span> العميل  </th>
            </tr>
        </thead>
        <tbody>
                <tr>
                <th> مجمل عدد الطلبات</th>
                <th> مجمل المبالغ </th>
                </tr>
                <tr>
                <td><span class="badge badge-info">{{$orders_count}}</span></td>
                <td>
                    @if($prices==0)
                    <span class="badge badge-warning">لم يتم دفع أي طلب</span>
                    <span class="badge badge-danger">{{$prices}}</span>
                    @else
                    <span class="badge badge-success">{{$prices}}</span>
                    @endif
                </td>
                </tr>
        </tbody>
    </table>
    
    
    </div>
@endsection
