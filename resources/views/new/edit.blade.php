@extends('layouts.admin')

@section('title', '新闻')

@section('content')
    <form action="{{url('new/update/'.$data->new_id)}}" method="post">
        <div class="form-group">
            <label for="exampleInputEmail1">新闻标题</label>
            <input type="text" class="form-control" value="{{$data->new_title}}" name="new_title" id="exampleInputEmail1">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">新闻作者</label>
            <input type="text" class="form-control" value="{{$data->new_author}}" name="new_author" id="exampleInputPassword1">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">新闻内容</label>
            <textarea class="form-control" rows="3" name="new_content">{{$data->new_content}}</textarea>
        </div>
        <button type="submit" class="btn btn-default">修改</button>
    </form>
@endsection