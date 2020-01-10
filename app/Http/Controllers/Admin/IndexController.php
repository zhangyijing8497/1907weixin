<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        return view('index.index');
    }


    public function index_v1()
    {
        return view('index.index_v1');
    }

    /**获取天气数据 */
    public function getWeather(Request $request)
    {
        $city = $request->city;
        $url = "http://api.k780.com/?app=weather.future&weaid=".$city."&appkey=47861&sign=77b2fb2102af64828f53f92aa12bb607&format=json";
        $sky = file_get_contents($url);
        return $sky; 
    }
}
