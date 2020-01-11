<?php

namespace App\Http\Controllers\Git;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        $cmd = "cd /data/wwwroot/default/1907weixin && git pull";
        shell_exec($cmd);
    }
}
