<?php
namespace Home\Controller;
use Think\Controller;
//允许跨域
class IndexController extends Controller {

    public function index($value='')
    {
        # code...
        $this->display();
    }
    /*************************/
    /*      调用接口      */
    /*************************/
    public function getAccess(){
        $appid = '17064811';
        $apikey = '8yXrED8VEAEeikX3iaiwqwla';
        $secretkey = 'e9oocA1ImyQti9iG9jUKWqcvQXhGllbT';
        $url = 'https://aip.baidubce.com/oauth/2.0/token';
        
        $access_token = S('access_token_'.date('m'));
        if ($access_token== null) {
            $post_data['grant_type']  = 'client_credentials';
            $post_data['client_id']  = $apikey;
            $post_data['client_secret'] =$secretkey;
            //var_dump($this->$apikey);
            $o = "";
            foreach ( $post_data as $k => $v ) 
            {
                $o.= "$k=" . urlencode( $v ). "&" ;
            }
            $post_data = substr($o,0,-1);
            $res = https_request($url, $post_data);
            $jsoninfo = json_decode($res, true);
           //var_dump($jsoninfo);
            if ($jsoninfo['access_token']) {
                # code...
                S('access_token_'.date('m'),$jsoninfo['access_token'],2592000);
            }
        }
        echo  $access_token;
    } 


    public function getPicture(){
        //$token = $this->getAccess();
       // $url = 'https://aip.baidubce.com/rest/2.0/face/v1/merge?access_token=' . $token;

        $image_template = array(
            'image' => 'sada',#模板图信息 图片的分辨率要求在1920x1080以下
            'image_type' => 'BASE64',#图片类型 BASE64:图片的base64值;URL:图片的 URL( 下载图片时可能由于网络等原因导致下载图片时间过长)FACE_TOKEN: 人脸标识
            'quality_control' => 'NONE'#质量控制 NONE: 不进行控制 LOW:较低 NORMAL: 一般 HIGH: 较高的质量要求。默认NONE
        );
        $image_target = array(
            'image' => 'asdasd',#目标图信息 图片的分辨率要求在1920x1080以下
            'image_type' => 'BASE64',#图片类型  BASE64      URL     FACE_TOKEN
            'quality_control' => 'NONE',#
            'merge_degree' => 'COMPLETE'#LOW:较低的融合度NORMAL: 一般的融合度HIGH: 较高的融合度COMPLETE完全
        );
        $temp = array(
            'image_template' => $image_template, 
            'image_target' => $image_template
        );
        $bodys = json_encode($temp);
        var_dump($bodys);
        //$bodys = "{\"image_template\":{\"image\":\"sfasq35sadvsvqwr5q...\",\"image_type\":\"BASE64\",\"quality_control\":\"NONE\"},\"image_target\":{\"image\":\"sfasq35sadvsvqwr5q...\",\"image_type\":\"BASE64\",\"quality_control\":\"NONE\"}}"
        
        //$res = request_post($url, $bodys);
        //var_dump($res);
    } 

    public function upload($value='')
    {
        # code...
        header("content-type:text/html;charset=utf-8");
        $base64_img = trim($_POST['img']);
        $up_dir = './upload/';//存放在当前目录的upload文件夹下
         
        if(!file_exists($up_dir)){
            mkdir($up_dir,0777);
        }
         
        if(preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_img, $result)){
            $type = $result[2];
            if(in_array($type,array('pjpeg','jpeg','jpg','gif','bmp','png'))){
                $new_file = $up_dir.date('YmdHis_').'.'.$type;
                if(file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_img)))){
                    $img_path = str_replace('../../..', '', $new_file);
                    echo '图片上传成功</br>![](' .$img_path. ')';
                }else{
                    echo '图片上传失败</br>';
         
                }
            }else{
                //文件类型错误
                echo '图片上传类型错误';
            }
         
        }else{
            //文件错误
            echo '文件错误';
        }
    }

    protected function https_request($url,$data = null){
        if(function_exists('curl_init')){
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($curl, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
            if (!empty($data)){
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            }
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($curl);
            curl_close($curl);
            return $output;
        }else{
            return false;
        }
    }


}