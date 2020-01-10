@extends('layouts.admin')

@section('title', '新闻管理--展示')

@section('content')
    <h3><b>新闻列表</b></h3>
    <form class="form-inline">
        <div class="form-group">
            <label for="exampleInputName2">新闻标题</label>
            <input type="text" class="form-control" name="new_title" value="{{$query['new_title']??''}}" id="exampleInputName2" placeholder="请输入新闻标题关键字">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail2">新闻作者</label>
            <input type="text" class="form-control" name="new_author" value="{{$query['new_author']??''}}" id="exampleInputEmail2" placeholder="请输入新闻作者关键字">
        </div>
        <button type="submit" class="btn btn-default">搜索</button>
    </form>
    <table class="table table-hover table-bordered">
        <tr>
            <th>新闻ID</th>
            <th>新闻标题</th>
            <th>新闻作者</th>
            <th>新闻内容</th>
            <th>时间</th>
            <th>访问量</th>
            <th>操作</th>
        </tr>
        @foreach($data as $k=>$v)
        <tr>
            <td>{{$v->new_id}}</td>
            <td>{{$v->new_title}}</td>
            <td>{{$v->new_author}}</td>
            <td>{{$v->new_content}}</td>
            <td>{{date('Y-m-d h:i:s',$v->add_time)}}</td>
            <td>{{$v->new_visit}}</td>
            <td>
                <a href="{{url('new/destroy/'.$v->new_id)}}" class="btn btn-danger">删除</a>
                <a href="{{url('new/edit/'.$v->new_id)}}" class="btn btn-primary">编辑</a>
            </td>
        </tr>
        @endforeach
    </table>
    {{$data->appends($query)->links()}}
@endsection