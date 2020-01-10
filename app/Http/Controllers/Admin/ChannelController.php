<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Tools\Wechat;
use App\Tools\Curl;
use App\Model\Channel;

class ChannelController extends Controller
{
    /**展示添加视图 */
    public function add()
    {
        return view('channel.add');
    }

    /**执行添加 */
    public function add_do(Request $request)
    {
        $data = $request->input();
        $access_token = Wechat::getAccessToken();//调用封装好的方法
        dd($access_token);

        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$access_token;
	// $postData = '{"expire_seconds": 2592000, "action_name": "QR_STR_SCENE", "action_info": {"scene": {"scene_str": "'.$data['c_status'].'"}}}';
        $postData = [
            'expire_seconds'=>2592000,
            'action_name'=>"QR_STR_SCENE",
            'action_info'=>[
                'scene'=>[
                    'scene_str'=>$data['c_status']
                ],
            ],
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);
        $res = Curl::post($url,$postData);
        $res = json_decode($res,true);
        // dd($res);
        $data['c_ticket'] = $res['ticket'];
        $post = Channel::create($data);
        if($post){
            echo "<script>alert('添加成功');location='/channel/index'</script>";
        }else{
            echo "<script>alert('添加失败');location='/channel/add'</script>";
        }
    }

    /**列表展示 */
    public function index()
    {
        // $access_token = Wechat::getAccessToken();//调用封装好的方法
        // dd($access_token);
        $data = Channel::get();
        return view('channel.index',['data'=>$data]);
    }
}
