@extends('layouts.admin')

@section('title', '素材管理--添加')

@section('content')
    <form action="{{url('media/add_do')}}" method="post" enctype="multipart/form-data">
        <h3>素材添加</h3>
        <div class="form-group">
            <label for="exampleInputEmail1">素材名称</label>
            <input type="text" class="form-control" name="media_name" id="exampleInputEmail1">
        </div>
        <div class="form-group">
            <label for="exampleInputFile">素材文件</label>
            <input type="file" name="file">
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">素材类型</label>
            <select class="form-control" name="media_type">
                <option value="1">临时</option>
                <option value="2">永久</option>
            </select>
        </div>
        <div class="form-group">
            <label for="exampleInputEmail1">素材格式</label>
            <select class="form-control" name="media_format">
                <option value="image">图片</option>
                <option value="video">视频</option>
                <option value="voice">语音</option>
            </select>
        </div>
        <button type="submit" class="btn btn-default">添加</button>
    </form>
@endsection