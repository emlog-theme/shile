<?php 
/**
 * 页面底部信息
 */
if(!defined('EMLOG_ROOT')) {exit('error!');}

if( !$_POST && !$_POST['action'] ){
	if($_POST['action']=='ajax_post'){
		echo "";
	}else{
		include View::getView('modules/mo-footer');
	}
}