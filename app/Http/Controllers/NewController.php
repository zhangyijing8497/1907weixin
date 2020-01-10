<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\NewModel;
use App\Tools\Wechat;
use App\Tools\Curl;

class NewController extends Controller
{
    /**列表展示 */
    public function index(Request $request)
    {
        $new_title = $request->new_title;
        $new_author = $request->new_author;
        $where = [];
        if($new_title){
            $where[] = ['new_title','like',"%".$new_title."%"];
        }
        if($new_author){
            $where[] = ['new_author','like',"%".$new_author."%"];
        }
        $query = $request->all();

        $data = NewModel::where($where)->paginate(2);
        return view('new.index',['data'=>$data,'query'=>$query]);
    } 

    /**展示添加视图 */
    public function create()
    {
        return view('new.create');
    }

    /**执行添加 */
    public function store(Request $request)
    {
        $post = $request->input();
        $post['add_time'] = time();
        $res = NewModel::create($post);
        if($res){
            echo "<script>alert('添加成功');location='index'</script>";
        }else{
            echo "<script>alert('添加失败');location='create'</script>";
        }
    }

    /**展示编辑视图 */
    public function edit($new_id)
    {
        $data = NewModel::where('new_id',$new_id)->first();
        return view('new.edit',['data'=>$data]);
    }

    /**执行编辑 */
    public function update(Request $request, $new_id)
    {
        $post = $request->input();
        $res = NewModel::where('new_id',$new_id)->update($post);
        if($res){
            echo "<script>alert('编辑成功');location='/new/index'</script>";
        }else{
            echo "<script>alert('编辑失败');location='/new/edit'</script>";
        }
    }

    /**删除 */
    public function destroy($new_id)
    {
        $res = NewModel::destroy($new_id);
        if($res){
            echo "<script>alert('删除成功');location='/new/index'</script>";
        }else{
            echo "<script>alert('删除失败');location='/new/index'</script>";
        }
    }

    /**关注回复 */
    public function weixin(){
        // $echostr = $_GET['echostr'];
        // echo $echostr;die;
        $xml = file_get_contents("php://input");
        file_put_contents('weixin.log',"\n".$xml."\n",FILE_APPEND);
        $obj = simplexml_load_string($xml);
        $MsgType = $obj->MsgType;
        $Event = $obj->Event;
        //echo 1;die;
        if($MsgType=='event'&&$Event=='subscribe'){
            $userData = Wechat::getUserInfoByOpenId($obj->FromUserName);
            $sex = $userData['sex'];
            $nickname = $userData['nickname'];
            if($sex==1){
                $sex = "先生";
            }elseif($sex==2){
                $sex = "女士";
            }else{
                $sex = "先生(女士)";
            }
            $msg = "欢迎".$nickname.$sex."关注本公众号";
            Wechat::responseText($obj,$msg);
        }
       
        if($MsgType=="text"){         
            $content = trim($obj->Content);
            if($content=='最新新闻'){
                $newText = NewModel::orderBy('new_id','desc')->first();
                $msg = "新闻标题: ".$newText['new_title']."\n新闻作者: ".$newText['new_author']."\n新闻内容: ".$newText['new_content'];
                Wechat::responseText($obj,$msg);
            }elseif(mb_strpos($content,"新闻+")!==false){
                $news = mb_substr($content,3);
                // dd($news);
                $sql = NewModel::where('new_title','like',"%$news%")->get()->toArray();
                if($sql){
                    $msg = "";
                    foreach($sql as $k=>$v){
                        NewModel::where('new_id','=',$v['new_id'])->increment('new_visit');
                        $msg = "新闻标题: " . $sql[0]['new_title'] . "\n新闻内容: " .$sql[0]['new_content'];
                    }
                    Wechat::responseText($obj,$msg);
                }else{
                    $msg = "暂无相关新闻";
                    Wechat::responseText($obj,$msg);
                }
            }
        }
    }
}
