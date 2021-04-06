@extends('layouts.app')

@section('content')
<table style="margin-top: 100px;" class="table table-bordered">
    <thead class="thead-light">
        <tr>
            <th><h5 class="float-right"> اضافة كلمة مفتاحية</th>
        </tr>
    </thead>
    <tbody>
        <form action="{{route('tags.store')}}" method="post">
            @csrf
            <tr>
                <td>
                    <label> الاسم بالعربي </label>
                    <input type="text" name="name_ar">
                </td>
                <td>
                    <label> الاسم بالاجنبي </label>
                    <input type="text" name="name_en">
                </td>
                <td>
                    <button type="submit" class="btn btn-success">حفظ</button>
                </td>
            </tr>
        </form>
    </tbody>
    </table>
    @if ($message = Session::get('success'))
<div class="alert alert-success alert-block">
	<button type="button" class="close" data-dismiss="alert">×</button>	
        <strong>{{ $message }}</strong>
</div>
@endif
@endsection