@extends('layouts.admin')

@section('title', '新闻管理--添加')

@section('content')
    <form action="{{url('new/store')}}" method="post">
        <div class="form-group">
            <label for="exampleInputEmail1">新闻标题</label>
            <input type="text" class="form-control" name="new_title" id="exampleInputEmail1">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">新闻作者</label>
            <input type="text" class="form-control" name="new_author" id="exampleInputPassword1">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">新闻内容</label>
            <textarea class="form-control" rows="3" name="new_content"></textarea>
        </div>
        <button type="submit" class="btn btn-default">添加</button>
    </form>
@endsection