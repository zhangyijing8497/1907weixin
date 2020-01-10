@extends('layouts.admin')

@section('title', '渠道管理--添加')

@section('content')
    <form action="{{url('channel/add_do')}}" method="post">
        <div class="form-group">
            <label for="exampleInputEmail1">渠道名称</label>
            <input type="text" class="form-control" name="c_name" id="exampleInputEmail1">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">渠道标识</label>
            <input type="text" class="form-control" name="c_status" id="exampleInputPassword1">
        </div>
        <button type="submit" class="btn btn-default">添加</button>
    </form>
@endsection