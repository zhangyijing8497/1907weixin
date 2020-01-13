<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Tools\Wechat;
use App\Tools\Curl;
use App\Model\Media;
use App\Model\Channel;
use App\Model\WechatUser;
use DB;

class WechatController extends Controller
{
    private $student = [
		'肖战',
		'王源',
		'王俊凯',
		'易烊千玺',
		'陈伟霆',
		'李易峰',
		'马天宇',
        '薛之谦',
        '任嘉伦',
        '王一博',
	];

    /**关注回复 */
    public function index(Request $request)
    {
        // $echostr = $_GET['echostr'];
        // echo $echostr;die;

        $xml = file_get_contents("php://input");//接受原始的xml或json数据流
        file_put_contents("log.txt","\n".$xml."\n",FILE_APPEND);//写入文件
        
        $xmlObj = simplexml_load_string($xml);

        $openid = $xmlObj->FromUserName;
        $msgType = $xmlObj->MsgType;
        $mediaId = $xmlObj->MediaId;
        if($msgType == 'image'){
            // 下载图片
            $this->downloadImg($mediaId);
        }elseif($msgType == 'video'){
            // 下载视频
            $this->downloadVideo($mediaId);
        }

        if($xmlObj->MsgType == "event" && $xmlObj->Event == "subscribe"){
            // 关注时获取用户的基本信息
            $data = Wechat::getUserInfoByOpenId($xmlObj->FromUserName);
            // dd($data);
            // 得到渠道标识
            $channel_status = $data['qr_scene_str'];
            // 根据渠道标识  关注人数递增
            Channel::where(['c_status'=>$channel_status])->increment('c_num');
            
            $res = WechatUser::where(['openid'=>$xmlObj->FromUserName])->first();
            if($res){
                // 修改用户表is_del状态
                WechatUser::where(['openid'=>$xmlObj->FromUserName])->update(['is_del'=>1,'c_status'=>$channel_status]);
            }else{
                // 将用户信息存入数据库和渠道标识
                $user_data = [
                    'openid'=>$data['openid'],
                    'subscribe_time'=>$data['subscribe_time'],
                    'headimgurl'=>$data['headimgurl'],
                    'c_status'=>$channel_status,
                    'sex'=>$data['sex'],
                    'nickname'=>$data['nickname']
                ];
                // dd($user_data);
                $userInfo = WechatUser::create($user_data);
            }

            $nickname = $data['nickname']; //取到用户昵称
            $msg = "欢迎".$nickname."关注";
            // dd($data);
            Wechat::responseText($xmlObj,$msg);
        }

        //取消关注 
        if($xmlObj->MsgType == "event" && $xmlObj->Event == "unsubscribe"){
            // 修改用户表的状态
            WechatUser::where(['openid'=>$xmlObj->FromUserName])->update(['is_del'=>2]);
            
            // 通过openid查询用户表得到渠道标识
            $firstUser = WechatUser::where(['openid'=>$xmlObj->FromUserName])->first();
            $c_status = $firstUser['c_status'];
            // 渠道表的c_num-1
            Channel::where(['c_status'=>$c_status])->decrement('c_num');
        }

        if($xmlObj->MsgType == "text"){
            $content = trim($xmlObj->Content);
            if($content == "1"){
                $msg = implode(',',$this->student);
                Wechat::responseText($xmlObj,$msg);
            }else if($content == "2"){
                $rand = array_rand($this->student,1);
                $msg = $this->student[$rand];
                Wechat::responseText($xmlObj,$msg);
            }else if(mb_strpos($content,"天气") !== false){
                $city = rtrim($content,"天气");
                if(empty($city)){
                    $city = "北京";
                } 
                $url = "http://api.k780.com/?app=weather.future&weaid=".$city."&appkey=47861&sign=77b2fb2102af64828f53f92aa12bb607&format=json";
                $data = file_get_contents($url);
                $data = json_decode($data,true);
                $msg = "";
                foreach($data['result'] as $k=>$v){
                    $msg .= $v['days']." 星期: ".$v['week']." 地方: ".$v['citynm']." 温度: ".$v['temperature']." 风向: ".$v['wind']."\n";
                }
                Wechat::responseText($xmlObj,$msg);
            }else{
                $msg = $xmlObj->Content;
                Wechat::responseText($xmlObj,$msg);
            }
        }
    }   
    
    /**自定义菜单 */
    public function createMenu()
    {
        $access_token = Wechat::getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
        // echo $url;die;
        $postData = [
            "button" => [
                [
                    "type"=>"click",
                    "name"=>"微信",
                    "key"=>"1907weixin"
                ],
                [
                    "name" => "二级菜单",
                    "sub_button" => [
                        [
                            "type" => "view",
                            "name" => "京东",
                            "url"=> "http://www.jd.com/"
                        ],
                        [
                            "name" => "发送位置", 
                            "type" => "location_select", 
                            "key" => "Sending location"
                        ],
                        [
                            "type" => "scancode_push", 
                            "name" => "扫码", 
                            "key" => "Sweep code",
                        ],
                        [
                            "type" => "pic_photo_or_album", 
                            "name" => "拍照或者相册发图", 
                            "key" => "Photograph",
                        ]
                    ]
                ]
            ],
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);
        $res = Curl::post($url,$postData);
        $res = json_decode($res,true);
        dd($res);
    }


    /**下载图片素材 */
    protected function downloadImg($mediaId)
    {
        $access_token = Wechat::getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$access_token.'&media_id='.$mediaId;
        // 请求获取素材接口
        $img = file_get_contents($url);
        $imgName = date('YmdHis').rand(1111,9999).'.jpg';
        // var_dump($img);
        file_put_contents($imgName,$img);
    }

    /**下载视频素材 */
    protected function downloadVideo($mediaId)
    {
        $access_token = Wechat::getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$access_token.'&media_id='.$mediaId;
        // 请求获取素材接口
        $video = file_get_contents($url);
        // 保存视频
        $file_name = date('YmdHis').rand(1111,9999).'.mp4';
        file_put_contents($file_name,$video);
    } 

    /**微信群发 */
    public function sendAllByOpenId()
    {
        $users = WechatUser::get()->toArray();
        // print_r($users);die;
        $openid_list = array_column($users,'openid');
        // print_r($openid_list);die;
        // $openid_list = [
        //     'obbcZwynJ2PnB4gdgG5hWlyGNmxg',
        //     'obbcZwzM3KGIy4p0O60Sn3na4fac',
        //     'obbcZwzFGIn4sRC_Ad1cdrh3BJdM',
        //     'obbcZw-WOBBw9WUIUgmOME9rMSV8'
        // ];
        $access_token = Wechat::getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=".$access_token;
        
        $msg = date('Y-m-d H:i:s')."你好!很高兴认识你";
        $postData = [
            "touser" =>$openid_list,
            "msgtype" =>"text",
            "text" =>[
                "content" =>$msg
            ]
        ];
        $postData = json_encode($postData,JSON_UNESCAPED_UNICODE);
        $res = Curl::post($url,$postData);
        $res = json_decode($res,true);
        // print_r($res);die;
        if($res['errcode'] == 0){
            echo "推送成功";
        }else{
            echo "错误信息: " .$res['errmsg'];
        }
    }

    public function test()
    {
        $redirect_uri = urlencode(env('WX_REDIRECT_URI'));
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".env('WX_APPID')."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
        echo $url;
    }

    /**接受网页授权code */
    public function auth()
    {
        $code = $_GET['code']; //接收code
        // 换取access_token
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.env('WX_APPID').'&secret='.env('WX_APPSEC').'&code='.$code.'&grant_type=authorization_code';
        $json_data = file_get_contents($url);
        $arr = json_decode($json_data,true);
        print_r($arr);

        // 获取用户信息
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$arr['access_token'].'&openid='.$arr['openid'].'&lang=zh_CN';
        $json_user_info = file_get_contents($url);
        $user_info_arr = json_decode($json_user_info,true);
        print_r($user_info_arr);
    }
    
}
