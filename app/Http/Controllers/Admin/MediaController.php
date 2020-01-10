<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Tools\Wechat;
use App\Tools\Curl;
use App\Model\Media;

class MediaController extends Controller
{
    /**素材添加 */
    public function add()
    {
        // $access_token = Wechat::getAccessToken();
        // echo $access_token;die;
        return view('media.add');
    }

    public function add_do(Request $request)
    {
        // 接收表单传过来的值
        $data = $request->input();
        /**文件上传 */
        $file = $request->file;
        $ext = $file->getClientOriginalExtension();//得到文件后缀名
        $filename = md5(uniqid()).".".$ext;
        $filePath = $request->file->storeAs('images',$filename);
        // var_dump($filePath);die;
        /** 调用微信上传素材接口 */
        $access_token = Wechat::getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=".$access_token."&type=".$data['media_format'];
        // echo $url;die;
        $filePathObj = new \CURLFile(public_path()."/".$filePath);
        $postData = ['media'=>$filePathObj];
        $res = Curl::post($url,$postData);
        $res = json_decode($res,true);
        // var_dump($res);die;
        if(isset($res['media_id'])){
            $media_id = $res['media_id'];
            // 入库
            $res1 = Media::create([
                'media_name' =>  $data['media_name'],
                'media_format' =>  $data['media_format'],
                'media_type' =>  $data['media_type'],
                'media_url' =>  $filePath,
                'wechat_media_id' =>  $media_id,
                'add_time' => time()
            ]);
            if($res){
                echo "<script>alert('添加成功');location='show'</script>";
            }else{
                echo "<script>alert('添加失败');location='add'</script>";
            }
        }
    }

    public function show()
    {
        $data = Media::get();
        return view('media.show',['data'=>$data]);
    }
}
