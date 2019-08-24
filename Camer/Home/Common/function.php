<?php

/**
* 渲染输出微信JSSDK配置请求
*  @param  appId: '', // 必填，公众号的唯一标识
*  @param  timestamp: , // 必填，生成签名的时间戳
 * @param  nonceStr: '', // 必填，生成签名的随机串
 * @param  signature: '',// 必填，签名，见附录1
 * @param  jsApiList: [] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
*/
function render_jssdk_config($appId,$timestamp,$nonceStr,$signature,$jsApiList){
    $str =  "".
            "   wx.config({                   ".
            "       debug: false,              ".
            "       appId:\"$appId\",         ".
            "       timestamp:\"$timestamp\", ".
            "       nonceStr:\"$nonceStr\",   ".
            "       signature:\"$signature\", ".
            "       jsApiList:$jsApiList      ".
            "   });                           ";

    echo $str;
}

/**
 * 获取当前页面完整URL地址
 * @return type 地址
 */
function get_url() {
    $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
    $php_self = $_SERVER['PHP_SELF'] ? safe_replace($_SERVER['PHP_SELF']) : safe_replace($_SERVER['SCRIPT_NAME']);
    $path_info = isset($_SERVER['PATH_INFO']) ? safe_replace($_SERVER['PATH_INFO']) : '';
    $relate_url = isset($_SERVER['REQUEST_URI']) ? safe_replace($_SERVER['REQUEST_URI']) : $php_self . (isset($_SERVER['QUERY_STRING']) ? '?' . safe_replace($_SERVER['QUERY_STRING']) : $path_info);
    return $sys_protocal . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . $relate_url;
}

/**
 * 安全过滤函数
 * @param $string
 * @return string
 */
function safe_replace($string) {
    $string = str_replace('%20', '', $string);
    $string = str_replace('%27', '', $string);
    $string = str_replace('%2527', '', $string);
    $string = str_replace('*', '', $string);
    $string = str_replace('"', '&quot;', $string);
    $string = str_replace("'", '', $string);
    $string = str_replace('"', '', $string);
    $string = str_replace(';', '', $string);
    $string = str_replace('<', '&lt;', $string);
    $string = str_replace('>', '&gt;', $string);
    $string = str_replace("{", '', $string);
    $string = str_replace('}', '', $string);
    $string = str_replace('\\', '', $string);
    return $string;
}

/**
 * 取文本中间
 * @param $string
 * @return string
 */
function getSubstr($str, $leftStr, $rightStr)
{
    $left = strpos($str, $leftStr);
    //echo '左边:'.$left;
    $right = strpos($str, $rightStr,$left);
    //echo '<br>右边:'.$right;
    if($left < 0 or $right < $left) return '';
    return substr($str, $left + strlen($leftStr), $right-$left-strlen($leftStr));
}



/**
 * emoji表情替换
 * @param $string
 * @return string
 */

function emoji($string) {
    $emoji_data= array("/大白菜Da学表情插件",
        "/惊讶","/撇嘴","/色","/发呆","/得意","/害羞","/闭嘴","/睡",
        "/流泪","/尴尬","/发怒","/调皮","/呲牙","/微笑","/难过","/酷",
        "/抓狂","/吐","/偷笑","/可爱","/白眼","/傲慢","/饥饿","/困",
        "/惊恐","/流汗","/憨笑","/大兵","/奋斗","/疑问","/嘘","/晕",
        "/衰","/骷髅","/敲打","/再见","/发抖","/爱情","/跳跳","/猪头",
        "/拥抱","/蛋糕","/闪电","/炸弹","/刀","/足球","/便便","/咖啡",
        "/饭","/玫瑰","/凋谢","/爱心","/心碎","/礼物","/太阳","/月亮",
        "/强","/弱","/握手","/飞吻","/跳跳","/西瓜","/冷汗","/抠鼻",
        "/鼓掌","/糗大了","/坏笑","/左哼哼","/右哼哼","/哈欠","/鄙视",
        "/委屈","/快哭了","/阴险","/亲亲","/吓","/可怜","/菜刀","/啤酒",
        "/篮球","/乒乓","/示爱","/瓢虫","/抱拳","/勾引","/拳头","/差劲",
        "/爱你","/NO","/OK");
    $emoji_img=array();
    $emoji_img[0]='<img src="http://fun.dbc2u.com/emoji/emoji.png">';
    for ($i=1; $i < count($emoji_data);$i++) { 
        $emoji_img[$i]='<img src="http://fun.dbc2u.com/emoji/'.$i.'.gif">';
    }

    for ($k=0; $k < count($emoji_data); $k++) { 
        $string = str_replace($emoji_data[$k],$emoji_img[$k], $string);
    }
    
    $string = str_replace('\\', '', $string);
    return $string;
}

/**
 * 判断是否是是来自微信浏览器访问
 * @return boolean $result
 */
function is_weixin(){
	if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
		return true;
	}	
	return false;
}

/**
 * 概率算法（适用于抽奖、随机广告） 
 */
function get_rand($rand_map) { 
    $rand_key = ''; 
 
    //概率数组的总概率精度 
    $proSum = array_sum($rand_map); 
 
    //概率数组循环 
    foreach ($rand_map as $key => $proCur) { 
        $randNum = mt_rand(1, $proSum); 
        if ($randNum <= $proCur) { 
            $rand_key = $key; 
            break; 
        } else { 
            $proSum -= $proCur; 
        } 
       // dump($randNum);
      //  dump($proSum);
    } 
   // dump($rand_map);

    unset($rand_map); 
 
    return $rand_key; 
}

/**
* 将索引数组转化为以某键的值为索引的数组
* @param array $list 要进行转换的数据集
* @param string $key 以该key为索引
*/
function array_key_list($list, $key='id'){
        $result = array();
        if(is_array($list)){
                foreach($list as $rs){
                        $result[$rs[$key]] = $rs;
                }
        }
        return $result;
}

function encode($string='', $skey ='cmspower') {
    $skey = str_split(base64_encode($skey));
    $strArr = str_split(base64_encode($string));
    $strCount = count($strArr);
    foreach ($skey as $key => $value) {
        $key < $strCount && $strArr[$key].=$value;
    }
    return str_replace('=', 'O0O0O', join('', $strArr));
}

function decode($string='', $skey='cmspower') {
    $skey = str_split(base64_encode($skey));
    $strArr = str_split(str_replace('O0O0O', '=', $string), 2);
    $strCount = count($strArr);
    foreach ($skey as $key => $value) {
        $key < $strCount && $strArr[$key][1] === $value && $strArr[$key] = $strArr[$key][0];
    }
    return base64_decode(join('', $strArr));
}

/**
 * 获取当前项目域名
 */
function get_domain(){
    /* 协议 */
    $protocol = ( isset($_SERVER ['HTTPS']) && (strtolower($_SERVER ['HTTPS']) != 'off')) ? 'https://' : 'http://' ;

    /* 域名或IP地址 */
    if ( isset($_SERVER ['HTTP_X_FORWARDED_HOST'])) {
        $host = $_SERVER ['HTTP_X_FORWARDED_HOST'];
    } elseif ( isset($_SERVER ['HTTP_HOST'])) {
        $host = $_SERVER ['HTTP_HOST'];
    } else {
        /* 端口 */
        if (isset ($_SERVER ['SERVER_PORT'])) {
            $port = ':' . $_SERVER[ 'SERVER_PORT'];
            if ((':80' == $port && 'http://' == $protocol ) || (':443' == $port && 'https://' == $protocol)) {
                $port = '' ;
            }
        } else {
            $port = '' ;
        }

        if (isset ($_SERVER ['SERVER_NAME'])) {
            $host = $_SERVER ['SERVER_NAME'] . $port;
        } elseif (isset ($_SERVER ['SERVER_ADDR'])) {
            $host = $_SERVER ['SERVER_ADDR'] . $port;
        }
    }

    return $protocol . $host ;
}

/**
**获取字符串长度
*/
function get_len($str){
preg_match_all('/./us', $str, $match); 
return count($match[0]);  // 输出9 
}

/**
**发送请求
*/
function https_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
/**
** baidu发送请求
*/
function request_post($url = '', $param = '') {
        if (empty($url) || empty($param)) {
            return false;
        }
        
        $postUrl = $url;
        $curlPost = $param;
        $curl = curl_init();//初始化curl
        curl_setopt($curl, CURLOPT_URL,$postUrl);//抓取指定网页
        curl_setopt($curl, CURLOPT_HEADER, 0);//设置header
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($curl, CURLOPT_POST, 1);//post提交方式
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($curl);//运行curl
        curl_close($curl);
        return $data;
    }


?>
