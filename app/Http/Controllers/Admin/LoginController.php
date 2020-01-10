<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\UserModel;

class LoginController extends Controller
{
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
