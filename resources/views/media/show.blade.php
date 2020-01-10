@extends('layouts.admin')

@section('title', '素材管理--展示')

@section('content')
    <h3><b>素材列表</b></h3>
    <table class="table table-hover table-bordered">
        <tr>
            <th>素材编号</th>
            <th>素材名称</th>
            <th>素材展示</th>
            <th>素材类型</th>
            <th>媒体格式</th>
            <!-- <th>微信服务器media_id</th> -->
            <th>添加时间</th>
        </tr>
        @foreach($data as $k=>$v)
        <tr>
            <td>{{$v->media_id}}</td>
            <td>{{$v->media_name}}</td>
            <td>
                @if($v->media_format=='image')
                    <img src="\{{$v->media_url}}" width="200px">
                @elseif($v->media_format=='voice')
                    <audio src="\{{$v->media_url}}" controls="controls" width="50px"></audio> 
                @elseif($v->media_format=='video')
                    <video src="\{{$v->media_url}}" controls="controls" width="200px"></video>
                @endif
            </td>
            <td>
                @if($v->media_type==1)
                    临时
                @else
                    永久
                @endif
            </td>
            <td>{{$v->media_format}}</td>
            <!-- <td>{{$v->wechat_media_id}}</td> -->
            <td>{{date('Y-m-d h:i:s',$v->add_time)}}</td>
        </tr>
        @endforeach
    </table>
@endsection