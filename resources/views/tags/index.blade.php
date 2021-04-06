@extends('layouts.app')

@section('content')
<table style="margin-top: 100px;" class="table table-bordered">
    <thead class="thead-light">
        <tr>
            <th><h5 class="float-right"> نظام الكلمات المفتاحية </th>
        </tr>
    </thead>
    <tbody>
            <tr style="display: flex; flex-direction:row-reverse">
            <td style="width: 100%"><a href="{{route('tags.create')}}" role="button" class="btn btn-success">اضافة</a> 
                </td>
            </tr>
            <tr style="display: flex; flex-direction:row-reverse">
                <td style="width: 100%">
                    <a href="" role="button" class="btn btn-primary">تعديل</a>
                </td>
            </tr>
    </tbody>
</table>
@endsection