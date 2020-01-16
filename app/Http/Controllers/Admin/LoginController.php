<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\UserModel;
use App\Tools\Wechat;
use App\Tools\Curl;

class LoginController extends Controller
{
    public function test()
    {
        $access_token = Wechat::getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
        $args = [
            "touser" => "obbcZwzFGIn4sRC_Ad1cdrh3BJdM",
            "template_id" => "qsBHYe94FHuqIBEjIYOPnpNWuOJO-vprFCZO1D-Mafk",
            "data" => [
                "code" => [
                    "value" => "123123",
                    "color" => "#173177"
                ],
                "name" => [
                    "value" => "肖战",
                    "color" => "#173177"
                ],
                "time" => [
                    "value" => date('Y-m-d H:i:s'),
                    "color" => "#173177"
                ],
            ],
        ];

        $args = json_encode($args,JSON_UNESCAPED_UNICODE);
        $res = Curl::post($url,$args);
        var_dump($res);die;
    }

    public function login()
    {
        return view('login.login');
    }

    public function doLogin()
    {
        $post = request()->except('_token');
        $where[] = ['username','=',$post['username']];
        $res = UserModel::where($where)->first();
        if($res){
                if($post['password']==$res['password']){
                    echo "<script>alert('登陆成功');location='index'</script>";
                }else{
                    if($res['code']>=3&&time()<$res['time']+60*2){
                        UserModel::where($where)->update(['code'=>0,'time'=>time()]);
                        echo "<script>alert('账号已锁定');location='login'</script>";
                    }else{
                        UserModel::where($where)->update(['code'=>$res['code']+1,'time'=>time()]);
                        echo "<script>alert('密码错误');location='login'</script>";
                    }
                }
        }else{
            echo "<script>alert('登陆失败');location='login'</script>";
        }
    }
}
