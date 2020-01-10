@extends('layouts.admin')

@section('title', '渠道管理--展示')

@section('content')
    <h3><b>渠道列表</b></h3>
    <table class="table table-hover table-bordered">
        <tr>
            <th>渠道编号</th>
            <th>渠道名称</th>
            <th>渠道标识</th>
            <th>渠道二维码</th>
            <th>关注人数</th>
            <th>操作</th>
        </tr>
        @foreach($data as $k=>$v)
        <tr>
            <td>{{$v->c_id}}</td>
            <td>{{$v->c_name}}</td>
            <td>{{$v->c_status}}</td>
            <td>
                <img src="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket={{$v->c_ticket}}" width="100px">
            </td>
            <td>{{$v->c_num}}</td>
            <td>
                <a href="javascript:;" class="btn btn-danger">删除</a>
                <a href="javascript:;" class="btn btn-primary">编辑</a>
            </td>
        </tr>
        @endforeach
    </table>
    <h3><b><a href="{{url('channel/add')}}">添加</a></b></h3>
@endsection