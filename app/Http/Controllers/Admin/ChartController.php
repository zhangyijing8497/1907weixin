<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Channel;

class ChartController extends Controller
{
    public function index()
    {
        $data = Channel::get()->toArray();
        $c_name = "";
        $c_num = "";
        foreach($data as $k=>$v){
            $c_name .= "'".$v['c_name']."',";
            $c_num .= $v['c_num'].",";
        }
        $c_name = rtrim($c_name,',');
        $c_num = rtrim($c_num,',');
        return view('chart.index',['c_name'=>$c_name,'c_num'=>$c_num]);
    }
}
