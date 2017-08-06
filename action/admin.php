<?php

require_once ('../../../../init.php');
error_reporting(0);
//header('Content-type:text/json');
global $CACHE;

$role='1';

if($role == 1){
    $ui = array();
    foreach ($_GET as $key => $value) {
        $ui[$key] = trim($value);
    }
}else{
    if( !$_POST){
        exit;
    }
    $ui = array();
    foreach ($_POST as $key => $value) {
        $ui[$key] = trim($value);
    }
}

if( empty($ui['action']) ){
    exit;
}
$db = Database::getInstance();
$user_cache = $CACHE->readCache('user');

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



switch ($ui['action']){
    case 'ajax_author_detail':
	
		$sql1 = "SELECT * FROM ".DB_PREFIX."comment where mail != '' and cid='{$ui['id']}' and hide ='n' ";
		$value = $db->fetch_array($db->query($sql1));
		
		$sql_side = "SELECT count(1) AS comment_nums,poster,mail,url,date,comment,cid,pid,gid FROM ".DB_PREFIX."comment where mail != '' and poster='{$value['poster']}' and hide ='n' group by mail order by comment_nums DESC ";
		$row = $db->fetch_array($db->query($sql_side));
		
		$old = date('Y',$row['date']);
		$now = date('Y');
		
		if( date('Y',$row['date']) < date('Y') ){
			$reply = '"'.ceil($now-$old).'年"前';
		}else{
			$reply = '今年';
		}
		echo "<div class=\"list-detail\"><div class=\"triangle\"><div></div></div><a href=\"{$row['url']}\" target=\"_blank\" class=\"author\"><img src=\"".cache_gravatar($row['mail'])."\" class=\"avatar avatar-46\" height=\"46\" width=\"46\">{$row['poster']}</a><span class=\"count\">总评论数：{$row['comment_nums']}</span><a href=\"".Url::comment($row['gid'])."\" class=\"earlist-comment\">第一次留言：{$row['comment']}</a><span>（这家伙从{$reply}开始在本博客留言！）</span></div>";
    break;
}

exit();