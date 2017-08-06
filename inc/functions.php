<?php 
/**
 * 局部函数库
 */
if(!defined('EMLOG_ROOT')) {exit('error!');}
error_reporting(0);
//模仿WordPress _g 转换 _hui or __ 获取模板设置参数
function _hui($type){return _g($type);}
function __($type){return _g($type);}
//index 分页函数
function static_page($count,$perlogs,$page,$url,$anchor=''){
    $pnums = @ceil($count / $perlogs);
    $page = @min($pnums,$page);
    $prepg=$page-1;//上一页
    $nextpg=($page==$pnums ? 0 : $page+1); //下一页
    $urlHome = preg_replace("|[\?&/][^\./\?&=]*page[=/\-]|","",$url);
    //开始分页导航内容
    $re = "<nav id=\"wide-page-navi\" class=\"page-navi\">";
    if($pnums<=1){
        return false;//如果只有一页则跳出
    }
    if($prepg){
        $re .="<a href=\"$url$prepg$anchor\">P+</a>";
    }else{
        $re .="";
    }
    for($i = $page-2;$i <= $page+2 && $i <= $pnums; $i++){
        if($i > 0){
            if($i == $page){
                $re .= "<span class='current'>$i</span>";
            }elseif($i == 1){
                $re .= "<a href=\"$urlHome$anchor\">$i</a>";
            }else{
                $re .= "<a href=\"$url$i$anchor\">$i</a>";
            }
        }
    }
	
    if($nextpg){
        $re .="<a href=\"$url$nextpg$anchor\" class='last'>N+</a>";
    }
	
    $re .="</nav>";
	
	
	$re .= "<nav id=\"narrow-page-navi\" class=\"page-navi\">";

	if($prepg){
		$re .="<div class=\"nav-previous\"><a href=\"$url$prepg$anchor\">上一页</a></div>";
	}else{
        $re .="";
    }

    if($nextpg){
        $re .="<div class=\"nav-next\"><a href=\"$url$nextpg$anchor\">下一页</a></div>";
    }		

	
	$re .= "</nav>";
	
    return $re;
}
function static_page_next($count,$perlogs,$page,$url,$anchor=''){
    $pnums = @ceil($count / $perlogs);
    $page = @min($pnums,$page);
    $prepg=$page-1;//上一页
    $nextpg=($page==$pnums ? 0 : $page+1); //下一页
    $urlHome = preg_replace("|[\?&/][^\./\?&=]*page[=/\-]|","",$url);
    //开始分页导航内容
    $re = "<ul class=\"pager\">";
    if($pnums<=1){
        return false;//如果只有一页则跳出
    }
    /*if($page!=1){
        $re .=" <a href=\"$urlHome$anchor\">首页</a> ";
    }*/
    if($prepg){
        $re .="<div class=\"nav-next\"><a href=\"$url$prepg$anchor\"><i class=\"fa fa-angle-double-left\"></i><span>上一页</span></a></div>";
    }else{
        $re .="<div class=\"nav-next\"><a href=\"javascript:;\"><i class=\"fa fa-angle-double-left\"></i><span>上一页</span></a></div>";
    }
    if($nextpg){
        $re .="<div class=\"nav-previous\"><a href=\"$url$nextpg$anchor\"><span>下一页</span><i class=\"fa fa-angle-double-right\"></i></a></div>";
    }
    $re .="</ul>";
    return $re;
}
function pic_thumb($content){
    preg_match_all("|<img[^>]+src=\"([^>\"]+)\"?[^>]*>|is", $content, $img);
    $imgsrc = !empty($img[1]) ? $img[1][0] : '';
    if($imgsrc):
        return $imgsrc;
    endif;
}
function blog_tool_purecontent($content, $strlen = null){
        $content = str_replace('继续阅读&gt;&gt;', '', strip_tags($content));
        if ($strlen) {
            $content = subString($content, 0, $strlen);
        }
        return $content;
}
//纯字符串
function clean( $string ){
	$string = trim( $string ); 
	$string = strip_tags( $string );
	$string = htmlspecialchars( $string, ENT_QUOTES, 'UTF-8' );
	$string = str_replace( "\n", "", $string );
	$string = trim( $string );
	return $string;
}
//CSS脚本加载
function _cssloader($arr) {
	foreach ($arr as $key => $item) {
		$href = $item;
		if (strstr($href, '//') === false) {
			$href = TEMPLATE_URL .$item.'.css?ver='.THEME_VER;
		}
		echo "<link rel='stylesheet' id='css-{$key}' href='{$href}' />\n";
	}
}
//JS脚本加载
function _jsloader($arr) {
	foreach ($arr as $item) {
		echo "<script type='text/javascript' src='".$item."'></script>\n";
	}
}
//模块文件加载
function _moloader($name = '') {
	return include View::getView('modules/'.$name);
}
//读取系统缓存信息
function option($name){
    global $CACHE;
    $options_cache = $CACHE->readCache('options');
    return $options_cache[$name];
}
//读取用户缓存信息
function user($uid,$type){
    global $CACHE;
    $user_cache = $CACHE->readCache('user');
    return $user_cache[$uid][$type];
}
//输出文章作者除链接
function blog_author_name($uid){
    global $CACHE;
    $user_cache = $CACHE->readCache('user');
    return $user_cache[$uid]['name'];
}
//输出缓存作者头像链接
function blog_author_img($uid){
	global $CACHE;
    $user_cache = $CACHE->readCache('user');
	return cache_gravatar($user_cache[$uid]['mail']);
}

//Gravatar+QQ头像缓存
function cache_gravatar($email, $s = 44, $d = 'identicon', $r = 'g'){
    $f = md5($email);
    $a = BLOG_URL.'content/avatars/'.$f.'.jpg';
    $e = EMLOG_ROOT.'/content/avatars/'.$f.'.jpg';
    $t = 1296000;
    if(empty($email)){
        $a = TEMPLATE_URL.'img/avatar-default.png';
    }
    if(!is_file($e) || (time() - filemtime($e)) > $t ){
        $g = sprintf("http://secure.gravatar.com",(hexdec($f{0})%2)).'/avatar/'.$f;
		copy($g,$e);
		$a=$g;
    }
    if(filesize($e) < 500){
        copy($d,$e);
    }
    return $a;
}
//自动为文章标签添加该标签的链接
function tag_link($content){
	global $CACHE;
	$tag_cache = $CACHE->readCache('tags');
	foreach($tag_cache as $value){
		$tag_url = Url::tag($value['tagurl']);
		$keyword = $value['tagname'];
		$cleankeyword = stripslashes($keyword);
		$url = "<a href=\"{$tag_url}\" title=\"浏览关于“{$cleankeyword}”的文章\" target=\"_blank\" class=\"tag_link\" >{$cleankeyword}</a>";
		$regEx = '\'(?!((<.*?)|(<a.*?)))('. $cleankeyword . ')(?!(([^<>]*?)>)|([^>]*?</a>))\'s';
		$content = preg_replace($regEx,$url,$content);
	}
	return $content;
}
//检测是否为手机
function em_is_mobile() {
    static $is_mobile;

    if ( isset($is_mobile) )
        return $is_mobile;

    if ( empty($_SERVER['HTTP_USER_AGENT']) ) {
        $is_mobile = false;
    } elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false ) {
            $is_mobile = true;
    } else {
        $is_mobile = false;
    }

    return $is_mobile;
}

function allow_doamin(){
	$is_allow=false;
	$url=trim($_SERVER['SERVER_NAME']);
	$arr_allow_domain=array(AUTHORIZE);//这里可以添加多个授权域名
	foreach($arr_allow_domain as $value){
		$value=trim($value);
		$tmparr=explode($value,$url);
		if(count($tmparr)>1){
			$is_allow=true;
			break;
		}
	}
	if(!$is_allow){
		die('域名未授权!');
	}
}
//根据时间G来判断
function get_time_type($t){
    if($t<=3){
        $ts = '拂晓';
    }elseif($t<=6){
        $ts = '黎明';
    }elseif($t<=9){
        $ts = '清晨';
    }elseif($t<=12){
        $ts = '早上';
    }elseif($t<=15){
        $ts = '中午';
    }elseif($t<=18){
        $ts = '下午';
    }elseif($t<=21){
        $ts = '傍晚';
    }elseif($t<=00){
        $ts = '深夜/午夜';
    }
    return $ts;
}
//评论时间格式获取 含时间段
function get_time_com($ptime){
    //$ptime = strtotime($time);
    $etime = time() - $ptime;
    if ($etime < 1) {
        return '刚刚';
    }
    $interval = array(
        12 * 30 * 24 * 60 * 60 => '年前 (' . date('Y-m-d', $ptime) . ')',
        30 * 24 * 60 * 60 => '个月前 (' . date('m-d', $ptime) . ')',
        7 * 24 * 60 * 60 => '周前 (' . date('m-d', $ptime) . ')',
        24 * 60 * 60 => '天前 (' . date('m-d', $ptime) . ')',
        60 * 60 => '小时前 <time class="new">'.get_time_type(date('G', $ptime)).'</time>',
        60 => '分钟前 <time class="new">'.get_time_type(date('G', $ptime)).'</time>',
        1 => '秒前 <time class="new">'.get_time_type(date('G', $ptime)).'</time>',
    );
    foreach ($interval as $secs => $str) {
        $d = $etime / $secs;
        if ($d >= 1) {
            $r = round($d);
            return $r . $str;
        }
    };
}
//评论时间格式获取 无时间段
function get_time($ptime){
    //$ptime = strtotime($time);
    $etime = time() - $ptime;
    if ($etime < 1) {
        return '刚刚';
    }
    $interval = array(
        12 * 30 * 24 * 60 * 60 => '年前',
        30 * 24 * 60 * 60 => '个月前',
        7 * 24 * 60 * 60 => '周前',
        24 * 60 * 60 => '天前',
        60 * 60 => '小时前',
        60 => '分钟前',
        1 => '秒前',
    );
    foreach ($interval as $secs => $str) {
        $d = $etime / $secs;
        if ($d >= 1) {
            $r = round($d);
            return $r . $str;
        }
    };
}
//网站源码压缩函数
function em_compress_html_main($buffer){
    $initial=strlen($buffer);
    $buffer=explode("<!--em-compress-html-->", $buffer);
    $count=count ($buffer);
    for ($i = 0; $i <= $count; $i++){
        if (stristr($buffer[$i], '<!--em-compress-html no compression-->')){
            $buffer[$i]=(str_replace("<!--em-compress-html no compression-->", " ", $buffer[$i]));
        }else{
            $buffer[$i]=(str_replace("\t", " ", $buffer[$i]));
            $buffer[$i]=(str_replace("\n\n", "\n", $buffer[$i]));
            $buffer[$i]=(str_replace("\n", "", $buffer[$i]));
            $buffer[$i]=(str_replace("\r", "", $buffer[$i]));
            while (stristr($buffer[$i], '  '))
            {
            $buffer[$i]=(str_replace("  ", " ", $buffer[$i]));
            }
        }
        $buffer_out.=$buffer[$i];
    }
    $final=strlen($buffer_out);
    $savings=($initial-$final)/$initial*100;
    $savings=round($savings, 2);
    $buffer_out.="<!--压缩前的大小: $initial bytes; 压缩后的大小: $final bytes; 节约：$savings% -->";
    return $buffer_out;
}
//内容页面禁止PRE压缩
function unCompress($content){
    if(preg_match_all('/(crayon-|<\/pre>)/i', $content, $matches)) {
        $content = '<!--em-compress-html--><!--em-compress-html no compression-->'.$content;
        $content.= '<!--em-compress-html no compression--><!--em-compress-html-->';
    }
    return $content;
}
//模板点赞功能
function likes($logid,$type='2'){
	if($type=='1'){
		$l = '<div><a href="javascript:;" class="like" data-pid="'.$logid .'" evt="likes" ><i class="fa fa-heart"></i>赞 (<span>'.(isset($log['praise']) ? $log['praise'] : likes_getNum($logid)).'</span>)</a></div>';
	}elseif($type=='2'){
		$l = '<a href="javascript:;" class="action action-like" data-pid="'.$logid.'" evt="likes"><i class="fa">&#xe60e;</i> 赞 (<span>'.(isset($log['praise']) ? $log['praise'] : likes_getNum($logid)).'</span>)</a>';
	}
	return $l;
}
function likes_getNum($logid){
	static $arr = array();
	$DB = Database::getInstance();
	if(isset($arr[$logid])) return $arr[$logid];
	$sql = "SELECT praise FROM " . DB_PREFIX . "blog WHERE gid=$logid";
	$res = $DB->query($sql);
	$row = $DB->fetch_array($res);
	$arr[$logid] = intval($row['praise']);
	return $arr[$logid];
}

//评论列表文章标题显示
function commtent_title($gid){
    $db = Database::getInstance();
    $sql = "SELECT * FROM ".DB_PREFIX."blog WHERE hide='n' and gid in ($gid) ORDER BY `date` DESC LIMIT 0,1";
    $list = $db->query($sql);
    while($row = $db->fetch_array($list)){
        return $row['title'].'上的评论';
    }
}
//文章详情页下相关文章
function related_logs($logData = array(),$log_num){
	if(is_file($configfile)){
		require $configfile;
	}else{
		$related_log_type = 'sort';//相关日志类型，sort为分类，tag为标签；
		$related_log_sort = 'views_desc';//排列方式，views_desc 为点击数（降序）comnum_desc 为评论数（降序） rand 为随机 views_asc 为点击数（升序）comnum_asc 为评论数（升序）
		$related_log_num = $log_num; //显示文章数
		$related_inrss = 'y'; //是否显示在rss订阅中，y为是，其它值为否
	}
	global $value;
	$DB = Database::getInstance();
	$CACHE = Cache::getInstance();
	extract($logData);
	if($value){
		$logid = $value['id'];
		$sortid = $value['sortid'];
		global $abstract;
	}
	$sql = "SELECT gid,title,date FROM ".DB_PREFIX."blog WHERE hide='n' AND type='blog'";
	if($related_log_type == 'tag'){
		$log_cache_tags = $CACHE->readCache('logtags');
		$Tag_Model = new Tag_Model();
		$related_log_id_str = '0';
		foreach($log_cache_tags[$logid] as $key => $val){
			$related_log_id_str .= ','.$Tag_Model->getTagByName($val['tagname']);
		}
		$sql .= " AND gid!=$logid AND gid IN ($related_log_id_str)";
	}else{
		$sql .= " AND gid!=$logid AND sortid=$sortid";
	}
	switch($related_log_sort){
		case 'views_desc':{
			$sql .= " ORDER BY views DESC";break;
		}
		case 'views_asc':{
			$sql .= " ORDER BY views ASC";
			break;
		}
		case 'comnum_desc':{
			$sql .= " ORDER BY comnum DESC";
			break;
		}
		case 'comnum_asc':{
			$sql .= " ORDER BY comnum ASC";
			break;
		}
		case 'rand':{
			$sql .= " ORDER BY rand()";
		break;
		}
	}
	$sql .= " LIMIT 0,$related_log_num";
	$related_logs = array();
	$query = $DB->query($sql);
	while($row = $DB->fetch_array($query)){
		$row['gid'] = intval($row['gid']);
		$row['title'] = htmlspecialchars($row['title']);
		$related_logs[] = $row;
	}
	$out = '';
	if(!empty($related_logs)){
		foreach($related_logs as $val){
			$out .= "<li><a href=\"".Url::log($val['gid'])."\" title=\"{$val['title']}\">{$val['title']}</a><time>".gmdate('Y-n-j', $val['date'])."</time></li>";
		}
	}
	if(!empty($value['content'])){
		if($related_inrss == 'y'){
			$abstract .= $out;
		}
	}else{
		return $out;
	}
}

function hui_get_adcode($name){
    if( !$name ) return '';
    if( em_is_mobile() ){
        return _hui($name.'_m');
    }else{
        return _hui($name);
    }
}

function baidu($url){
	$url='http://www.baidu.com/s?wd='.$url;
	$curl=curl_init();
	curl_setopt($curl,CURLOPT_URL,$url);
	curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
	$rs=curl_exec($curl);
	curl_close($curl);
	if(!strpos($rs,'没有找到')){
		return 1;
	}else{
		return 0;
	}
}
function check_baidu($id){
	$url=Url::log($id);
	if(baidu($url)==1){
		echo "<span class=\"item\">百度已收录</span>";
	}else{
		echo "<span class=\"item\"><a style=\"color:red;\" rel=\"external nofollow\" title=\"点击提交收录！\" target=\"_blank\" href=\"http://zhanzhang.baidu.com/sitesubmit/index?sitename=$url\">百度未收录</a></span>";
	}
}

//获取评论用户操作系统、浏览器等信息
function useragent($info){
	require_once View::getView('inc/useragent.class');
	$useragent = UserAgentFactory::analyze($info);
	if(empty($info)){
		$platform='';
		$browser='';
	}else{
		if($useragent->os['title']=="Unknown"){
			$platform='<img src="'.TEMPLATE_URL.'img/16/browser/null.png">&nbsp;非正常操作系统&nbsp;&nbsp;';
		}else{
			$platform='<img src="'.TEMPLATE_URL.$useragent->os['image'].'">&nbsp;'.$useragent->os['title'].'&nbsp;&nbsp;';
		}
		if($useragent->browser['title']=="Unknown"){
			$browser='<img src="'.TEMPLATE_URL.'img/16/browser/null.png">&nbsp;非正常浏览器&nbsp;&nbsp;';
		}else{
			$browser='<img src="'.TEMPLATE_URL.$useragent->browser['image'].'">&nbsp;'.$useragent->browser['title'].'&nbsp;&nbsp;';
		}
	}
	return  $platform.$browser;
}

function convertip($ip){
    $dat_path = EMLOG_ROOT . '/content/ip.dat';
    //*数据库路径*//
    if (!($fd = @fopen($dat_path, 'rb'))) {
        return 'IP数据库文件不存在或者禁止访问或者已经被删除！';
    }
    $ip = explode('.', $ip);
    $ipNum = $ip[0] * 16777216 + $ip[1] * 65536 + $ip[2] * 256 + $ip[3];
    $DataBegin = fread($fd, 4);
    $DataEnd = fread($fd, 4);
    $ipbegin = implode('', unpack('L', $DataBegin));
    if ($ipbegin < 0) {
        $ipbegin += pow(2, 32);
    }
    $ipend = implode('', unpack('L', $DataEnd));
    if ($ipend < 0) {
        $ipend += pow(2, 32);
    }
    $ipAllNum = ($ipend - $ipbegin) / 7 + 1;
    $BeginNum = 0;
    $EndNum = $ipAllNum;
    while ($ip1num > $ipNum || $ip2num < $ipNum) {
        $Middle = intval(($EndNum + $BeginNum) / 2);
        fseek($fd, $ipbegin + 7 * $Middle);
        $ipData1 = fread($fd, 4);
        if (strlen($ipData1) < 4) {
            fclose($fd);
            return '系统出错！';
        }
        $ip1num = implode('', unpack('L', $ipData1));
        if ($ip1num < 0) {
            $ip1num += pow(2, 32);
        }
        if ($ip1num > $ipNum) {
            $EndNum = $Middle;
            continue;
        }
        $DataSeek = fread($fd, 3);
        if (strlen($DataSeek) < 3) {
            fclose($fd);
            return '系统出错！';
        }
        $DataSeek = implode('', unpack('L', $DataSeek . chr(0)));
        fseek($fd, $DataSeek);
        $ipData2 = fread($fd, 4);
        if (strlen($ipData2) < 4) {
            fclose($fd);
            return '系统出错！';
        }
        $ip2num = implode('', unpack('L', $ipData2));
        if ($ip2num < 0) {
            $ip2num += pow(2, 32);
        }
        if ($ip2num < $ipNum) {
            if ($Middle == $BeginNum) {
                fclose($fd);
                return '未知';
            }
            $BeginNum = $Middle;
        }
    }
    $ipFlag = fread($fd, 1);
    if ($ipFlag == chr(1)) {
        $ipSeek = fread($fd, 3);
        if (strlen($ipSeek) < 3) {
            fclose($fd);
            return '系统出错！';
        }
        $ipSeek = implode('', unpack('L', $ipSeek . chr(0)));
        fseek($fd, $ipSeek);
        $ipFlag = fread($fd, 1);
    }
    if ($ipFlag == chr(2)) {
        $AddrSeek = fread($fd, 3);
        if (strlen($AddrSeek) < 3) {
            fclose($fd);
            return '系统出错！';
        }
        $ipFlag = fread($fd, 1);
        if ($ipFlag == chr(2)) {
            $AddrSeek2 = fread($fd, 3);
            if (strlen($AddrSeek2) < 3) {
                fclose($fd);
                return '系统出错！';
            }
            $AddrSeek2 = implode('', unpack('L', $AddrSeek2 . chr(0)));
            fseek($fd, $AddrSeek2);
        } else {
            fseek($fd, -1, SEEK_CUR);
        }
        while (($char = fread($fd, 1)) != chr(0)) {
            $ipAddr2 .= $char;
        }
        $AddrSeek = implode('', unpack('L', $AddrSeek . chr(0)));
        fseek($fd, $AddrSeek);
        while (($char = fread($fd, 1)) != chr(0)) {
            $ipAddr1 .= $char;
        }
    } else {
        fseek($fd, -1, SEEK_CUR);
        while (($char = fread($fd, 1)) != chr(0)) {
            $ipAddr1 .= $char;
        }
        $ipFlag = fread($fd, 1);
        if ($ipFlag == chr(2)) {
            $AddrSeek2 = fread($fd, 3);
            if (strlen($AddrSeek2) < 3) {
                fclose($fd);
                return '系统出错！';
            }
            $AddrSeek2 = implode('', unpack('L', $AddrSeek2 . chr(0)));
            fseek($fd, $AddrSeek2);
        } else {
            fseek($fd, -1, SEEK_CUR);
        }
        while (($char = fread($fd, 1)) != chr(0)) {
            $ipAddr2 .= $char;
        }
    }
    fclose($fd);
    if (preg_match('/http/i', $ipAddr2)) {
        $ipAddr2 = '';
    }
    $ipaddr = "{$ipAddr1} {$ipAddr2}";
    $ipaddr = preg_replace('/CZ88.Net/is', '', $ipaddr);
    $ipaddr = preg_replace('/^s*/is', '', $ipaddr);
    $ipaddr = preg_replace('/s*$/is', '', $ipaddr);
    if (preg_match('/http/i', $ipaddr) || $ipaddr == '') {
        $ipaddr = '未知';
    }
    $ipaddr = iconv('gbk', 'utf-8//IGNORE', $ipaddr);
    if ($ipaddr != '  ') {
        return $ipaddr;
    } else {
        $ipaddr = '评论者来自火星，无法或者其所在地!';
    }
    return $ipaddr;
}

function reader_wall_side($side){
	$CACHE = Cache::getInstance();
	$user_cache = $CACHE->readCache('user');
	if($side=='1'){
		$time_side = strtotime('last Monday',strtotime('Sunday'));
	}elseif($side=='2'){
		$time_side = strtotime('this month',strtotime(date('m/01/y')));
	}else{
		$time_side = 0;
	}
	$DB = Database::getInstance();
	$userName = $user_cache[1]['name'];
	$sql_side = "SELECT count(1) AS comment_nums,poster,mail,url,comment,gid FROM ".DB_PREFIX."comment where date > $time_side and mail != '' and poster != '$userName' and hide ='n' group by mail order by comment_nums DESC limit 0,6";
	$result_side = $DB->query($sql_side);
	
	while($row_side = $DB->fetch_array($result_side)){
		$img_side = "<img class=\"avatar avatar-36 photo\" width='36' height='36' alt='' src='".cache_gravatar($row_side['mail'])."' />";
		$tmp_side = "<li><a href=\"{$row_side['url']}\" target=\"_blank\" ><img src=\"".cache_gravatar($row_side['mail'])."\" class=\"avatar avatar-46\" height=\"46\" width=\"46\"></a>
						<span class=\"author\" style=\"display:none\"></span>
						<div class=\"detail\">
							<a href=\"{$row_side['url']}\" target=\"_blank\" class=\"author\"><img src=\"".cache_gravatar($row_side['mail'])."\" class=\"avatar avatar-46\" height=\"46\" width=\"46\">{$row_side['poster']}</a>
							<span class=\"count\">总评论数：{$row_side['comment_nums']}</span>
							<a href=\"".Url::comment($row_side['gid'])."\" class=\"recent-comment\">第一次留言：{$row_side['comment']}</a>
							<div class=\"triangle\"><div></div></div>
						</div>
					</li>";
		$output_side .= $tmp_side;
	}
	
	return $output_side;
}


















