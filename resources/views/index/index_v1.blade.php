@extends('layouts.admin')

@section('title', '天气--展示')

@section('content')
<meta charset="utf-8"><link rel="icon" href="https://jscdn.com.cn/highcharts/images/favicon.ico">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            /* css 代码  */
        </style>
        <script src="https://code.highcharts.com.cn/highcharts/highcharts.js"></script>
        <script src="https://code.highcharts.com.cn/highcharts/highcharts-more.js"></script>
        <script src="https://code.highcharts.com.cn/highcharts/modules/exporting.js"></script>
        <script src="https://img.hcharts.cn/highcharts-plugins/highcharts-zh_CN.js"></script>
        
<center><h3><b>一周气温展示</b></h3></center>
<form class="form-inline">
    <div class="form-group">
        <label for="exampleInputName2">城市</label>
        <input type="text" name="city" class="form-control">
    </div>
    <button type="button" class="btn btn-default" id="search">搜索</button><b style="color:red;">(城市名可以为拼音或汉字)</b>
</form>
<script src="/admin/js/js.js"></script>
<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<script>
    $(document).ready(function(){
        $(document).on("click","#search",function(){
            var city = $('input[name="city"]').val();
            if(city == ""){
                city="北京";
            }

            // 正则 可以是汉字和拼音
            var reg = /^[a-zA-Z]+$|^[\u4e00-\u9fa5]+$/;
            if(!reg.test(city)){
                alert('城市名只能为拼音和汉字');
                return;
            }
            $.ajax({
                url:"{{url('admin/getWeather')}}",
                data:{city:city},
                dataType:"json",
                success:function(res){
                    // 展示天气图表
                    // console.log(res);
                    weater(res.result);
                }
            })
        })

        function weater(res){
            var days=[];
            var temp_low=[];
            $.each(res,function(i,v){
                days.push(v.days);
                var arr=[parseInt(v.temp_low),parseInt(v.temp_high)];
                temp_low.push(arr);
            })


            var chart = Highcharts.chart('container', {
                chart: {
                    type: 'columnrange', // columnrange 依赖 highcharts-more.js
                    inverted: true
                },
                title: {
                    text: '每月温度变化范围'
                },
                subtitle: {
                    text: res[0]['citynm']
                },
                xAxis: {
                    categories: days
                },
                yAxis: {
                    title: {
                        text: '温度 ( °C )'
                    }
                },
                tooltip: {
                    valueSuffix: '°C'
                },
                plotOptions: {
                    columnrange: {
                        dataLabels: {
                            enabled: true,
                            formatter: function () {
                                return this.y + '°C';
                            }
                        }
                    }
                },
                legend: {
                    enabled: false
                },
                series: [{
                    name: '温度',
                    data: temp_low
                }]
            });
        }





    })








                
</script>

@endsection