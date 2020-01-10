@extends('layouts.admin')

@section('title', '渠道管理--展示')

@section('content')
    <!-- 图表容器 DOM -->
    <div id="container" style="width: 600px;height:400px;"></div>
    <!-- 引入 highcharts.js -->
    <script src="http://cdn.highcharts.com.cn/highcharts/highcharts.js"></script>
    <script>
        // 图表配置
        var options = {
            chart: {
                type: 'bar'                          //指定图表的类型，默认是折线图（line）
            },
            title: {
                text: '渠道统计图表'                 // 标题
            },
            xAxis: {
                categories: [<?php echo $c_name?>]   // x 轴分类
            },
            yAxis: {
                title: {
                    text: '人数'                // y 轴标题
                }
            },
            series: [{                              // 数据列
                name: '公众号关注人数',                        // 数据列名
                data: [<?php echo $c_num?>]                     // 数据
            }]
        };
        // 图表初始化函数
        var chart = Highcharts.chart('container', options);
    </script>
@endsection