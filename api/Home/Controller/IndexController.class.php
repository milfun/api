<?php
namespace Home\Controller;
use Think\Controller;
//允许跨域
class IndexController extends Controller {
    private function checkKey() {
        $secretKey = 'MilFun';
        $secret = I('get.secretKey',"","trim");
        if($secret != $secretKey){
            $this->ajaxReturn(2,'秘钥错误！',$list);
        }
    }

    public function index(){
    	/*header("Content-Type: text/html;charset=utf-8");
		header('Access-Control-Allow-Origin:*');
		header('Access-Control-Allow-Methods:OPTIONS, GET, POST'); 
		header('Access-Control-Allow-Headers:x-requested-with'); //
		$a=M('Article');
        $data2['aid']=10001;
        $list=$a->where($data2)->select();
    	//echo $data;
      	$this->ajaxReturn($list);*/
    }
    public function getarticle()//获取米饭日记文章内容
    {
        
        header("Content-Type: text/html;charset=utf-8");
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Methods:OPTIONS, GET, POST'); 
        header('Access-Control-Allow-Headers:x-requested-with'); //
        $a=M('Article');
        $data['type']=1;
        $list=$a->where($data)->order('aid desc')->find();
        //echo $data;
        if ($list) {
            $data['status']=1;
            $data['info']='获取数据成功！';
            $data['list']=$list;
        }else{
            $data['status']=0;
            $data['info']='获取数据失败！';
        }
        $this->ajaxReturn($data);
    }
    public function articlelist()//获取评论
    {
       // $lid=1I('get.lid');
        $lid=1;
        $data['lid']=$lid;
        $bu=M('Article');
        $list=$bu->where($data)->order('aid desc')->select(); 
        if ($list) {
            $data['status']=1;
            $data['info']='获取数据成功！';
            $data['list']=$list;
        }else{
            $data['status']=0;
            $data['info']='获取数据失败！';
        }
        $this->ajaxReturn($data);
    }
    public function milfun($value='')//提交文章
    {
    	$data['aname']=I('post.s');
        //$data['acontent']='<pre>'.$_POST["c"].'</pre>';
        $data['acontent']=I('post.c');
        $data['aid']=sha1(date('Y-m-d H:i:s').'MilFun');
        $data['imgurl']='http://milfun.fun/s/blog/img/logo.jpg';
        $data['author']='MilFun';
        $data['date']=date('Y-m-d H:i:s');
        $data['likes']=10;
        $data['hot']=100;
        $su=M('Blog');
        $info=$su->add($data);
        $data['status']=1;
        $data['info']='MilFun oks!';
        $this->AjaxReturn($data);
    }

    /*********************/
    /*   小程序签到功能   */
    /*********************/
    public function sign()
    {
    	# code...
        //查询条件
        $data['nickname']=I('post.nickname');
        $data['signterm']=I('post.signterm');
        $data['usergender']=sha1(date('Y-m-d'));
        //var_dump(I('nickname'));
        $s=M('Sign');
        $info=$s->where($data)->find();
        if($info){
        //如果对应类型的签到已经签了，时间有存在。
            $data['status']=0;
            $data['info']='你今天已经签到过了哦！';
        }else{
            $data['headimg']=I('post.headimg');
            $data['date']=date('Y-m-d H:i:s');
            /**判断是否签到过**/
            $s=M('Sign');
            $info=$s->add($data);
            $data['status']=1;
            $data['info']='签到成功!';
        }
        $this->AjaxReturn($data);
    }
    public function signList()
    {
    	# code...
    	 $data['signterm']=I('get.sid');
    	 $s=M('Sign');
    	 $data['usergender']=sha1(date('Y-m-d'));
    	 $info=$s->where($data)->order('date desc')->select();
    	 $this->AjaxReturn($info);
    	 //return $info;
    }
    /*************************/
    /*      微信调用接口      */
    /*************************/
    public function get_openid($code){
        $appid = 'wx34c3b96a26969a8c';
        $appsecret = '089719ce83d469c0681de020b3916db4';
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$appid.'&secret='.$appsecret.'&js_code='.$code.'&grant_type=authorization_code';
      //$url = 'https://api.weixin.qq.com/sns/jscode2session?appid=wx653988c2bc5d543f&secret=53816d431f4adbd1c16ab670888e8199&js_code='.$code.'&grant_type=authorization_code';
        $result = https_request($url);
        $jsoninfo = json_decode($result, true);
        $openid = $jsoninfo["openid"];
        if($openid){ 
            $this->ajaxReturn($openid);
        }else{
            $this->ajaxReturn('获取数据失败');
        }
        //$this->ajaxReturn(1,'获取数据成功',$openid);
    } 
    public function getWxUerinfo($code){
         $appid = 'wx34c3b96a26969a8c';
        $appsecret = '089719ce83d469c0681de020b3916db4';
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$appid.'&secret='.$appsecret.'&js_code='.$code.'&grant_type=authorization_code';
        //$url = 'https://api.weixin.qq.com/sns/jscode2session?appid=wx653988c2bc5d543f&secret=53816d431f4adbd1c16ab670888e8199&js_code='.$code.'&grant_type=authorization_code';C('qscms_weixinapp_appid')
        $result = https_request($url);
        $jsoninfo = json_decode($result, true);
        if($jsoninfo){
            $data['status']=1;
            $data['info']='获取数据成功！';
            $data['data']=$jsoninfo;
            $this->ajaxReturn($data);
        }else{
            $data['status']=0;
            $data['info']='获取数据失败！';
            $this->ajaxReturn($data);
        }
    } 

}