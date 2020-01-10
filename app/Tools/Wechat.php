<?php

namespace App\Tools;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Wechat
{
    
    const appId = "wxaa3fdba21e822298";
    const appSerect = "14618ca21e115be6cbca4de84c145e42";

    /**
     * 回复文本消息
     */
    public static function responseText($xmlObj,$msg)
	{
		echo "<xml>
			<ToUserName><![CDATA[".$xmlObj->FromUserName."]]></ToUserName>
			<FromUserName><![CDATA[".$xmlObj->ToUserName."]]></FromUserName>
			<CreateTime>".time()."</CreateTime>
			<MsgType><![CDATA[text]]></MsgType>
			<Content><![CDATA[".$msg."]]></Content>
		</xml>";die;
    }
    
    /**
     * 获取access_token
     */
    public static function getAccessToken()
    {
        // 先判断缓存是否有数据
        $access_token = Cache::get('access_token');
        // 有数据之前返回
        // if(empty($access_token)){
            // 获取access_token(调用接口凭证)
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".Self::appId."&secret=".Self::appSerect;
            $data = file_get_contents($url);
            $data = json_decode($data,true);
            $access_token = $data['access_token'];

        //     Cache::put('access_token',$access_token,7200); //2小时
        // }

        // 没有数据再调用接口 存入缓存
        return $access_token;        
    }

    /**
     * 获取用户信息
     */
    public static function getUserInfoByOpenId($openid)
    {
        $access_token = Self::getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
        $data = file_get_contents($url);
        $data = json_decode($data,true);
        return $data;
    }
}
